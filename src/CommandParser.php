<?php namespace BapCat\Console;

use BapCat\Values\ClassName;
use BapCat\Values\Regex;
use BapCat\Values\Text;

use ICanBoogie\Inflector;

use ReflectionClass;
use ReflectionMethod;

class CommandParser {
  private $registry;
  
  private $regex_params;
  private $regex_opts;
  private $text_dash;
  private $text_underscore;
  private $inflector;
  
  public function __construct(CommandRegistry $registry) {
    $this->registry = $registry;
    
    $this->regex_params = new Regex('#@param\h+?([\w\\\\]+)\h+?\$(\w+)\h+(?:\(short:\h(\w)\)\h+)?([\w\h]+)#');
    $this->regex_opts   = new Regex('#@opt\h+?\$(\w+)\h+(?:\(short:\h(\w)\)\h+)?([\w\h]+)#');
    
    $this->text_dash       = new Text('-');
    $this->text_underscore = new Text('_');
    
    $this->inflector = Inflector::get();
  }
  
  public function parse(ClassName $class) {
    $methods = $this->getMethods($class);
    
    $commands = new CommandCollection();
    
    foreach($methods as $method) {
      $name = $this->underscoreToDash(new Text($this->inflector->underscore($method->getName())));
      
      $method_params = $this->getMethodParameters($method);
      $method_docs   = $this->getMethodDocBlock($method);
      
      $doc_params = $this->getParamsFromDocs($method_docs);
      $doc_opts   = $this->getOptionsFromDocs($method_docs);
      
      $command = $this->buildCommand($name, $method_params, $doc_params, $doc_opts);
    
      //$this->validateClass($params, $opts);
      
      $commands->add($command);
    }
    
    return new CommandGroup($class, $commands);
  }
  
  private function getMethods(ClassName $class) {
    $reflector = $class->reflect();
    $methods = $reflector->getMethods(ReflectionMethod::IS_PUBLIC);
    return $methods;
  }
  
  private function getMethodDocBlock(ReflectionMethod $method) {
    $docs = new Text($method->getDocComment());
    return $docs;
  }
  
  private function getMethodParameters(ReflectionMethod $method) {
    $params = $method->getParameters();
    return $params;
  }
  
  private function getParamsFromDocs(Text $method_docs) {
    $doc_params = [];
    
    foreach($this->regex_params->capture($method_docs) as $doc_param) {
      $doc_params[(string)$doc_param[1]] = [
        $doc_param[0], // Type
        $doc_param[3], // Desc
        $doc_param[2]  // Short
      ];
    }
    
    return $doc_params;
  }
  
  private function getOptionsFromDocs(Text $method_docs) {
    $doc_opts = [];
    
    foreach($this->regex_opts->capture($method_docs) as $doc_opt) {
      $doc_opts[(string)$doc_opt[0]] = [
        $doc_opt[2], // Desc
        $doc_opt[1]  // Short
      ];
    }
    
    return $doc_opts;
  }
  
  private function buildCommand(Text $name, array $method_params, array $doc_params, array $doc_opts) {
    $params = new ParameterCollection();
    $opts   = new OptionCollection();
    $bad    = []; // Undocumented params
    
    while(count($method_params) != 0) {
      $current = array_pop($method_params);
      
      if(array_key_exists($current->getName(), $doc_params)) {
        $doc_param = $doc_params[$current->getName()];
        
        $params->add(new Parameter(
          new ClassName($current->getClass()->getName()),
          $this->underscoreToDash(new Text($current->getName())),
          $doc_param[1],
          !$doc_param[2]->is_empty ? $doc_param[2] : null,
          $current->isDefaultValueAvailable()
        ));
      } elseif(array_key_exists($current->getName(), $doc_opts)) {
        $doc_opt = $doc_opts[$current->getName()];
        
        $opts->add(new Option(
          $this->underscoreToDash(new Text($current->getName())),
          $doc_opt[0],
          !$doc_opt[1]->is_empty ? $doc_opt[1] : null
        ));
      } else {
        $bad[] = $current;
      }
    }
    
    if(count($bad) != 0) {
      //@TODO
      var_dump($bad);
      die('Bad commad def');
    }
    
    return new Command($name, $params, $opts);
  }
  
  private function validateClass(ParameterCollection $params, OptionCollection $opts) {
    //@TODO
  }
  
  private function underscoreToDash(Text $text) {
    return $text->replace($this->text_underscore, $this->text_dash);
  }
}
