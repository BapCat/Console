<?php namespace BapCat\Console;

use BapCat\Values\ClassName;
use BapCat\Values\Regex;
use BapCat\Values\Text;

use ReflectionClass;
use ReflectionMethod;

class CommandParser {
  private $regex_params;
  private $regex_opts;
  
  public function __construct() {
    $this->regex_params = new Regex('#@param\h+?([\w\\\\]+)\h+?\$(\w+)\h+(?:\(short:\h(\w)\)\h+)?([\w\h]+)#');
    $this->regex_opts   = new Regex('#@opt\h+?\$(\w+)\h+(?:\(short:\h(\w)\)\h+)?([\w\h]+)#');
  }
  
  public function parse(ClassName $command_class) {
    $docs   = $this->getDocBlock($command_class);
    $params = $this->getParams($docs);
    $opts   = $this->getOptions($docs);
    
    $this->validateClass($params, $opts);
    
    return new Executable($command_class, $params, $opts);
  }
  
  private function getDocBlock(ClassName $command_class) {
    $class = new ReflectionClass((string)$command_class);
    $constructor = $class->getConstructor();
    $docs = new Text($constructor->getDocComment());
    return $docs;
  }
  
  private function getParams(Text $docs) {
    $params = $this->regex_params->capture($docs);
    
    return new ParameterCollection(array_map(function(array $param) {
      return new Parameter(new ClassName((string)$param[0]), $param[1], $param[3], !$param[2]->isEmpty() ? $param[2] : null);
    }, $params));
  }
  
  private function getOptions(Text $docs) {
    $opts = $this->regex_opts->matches($docs);
    
    return new OptionCollection(array_map(function(array $opt) {
      return new Option($opt[0], $opt[2], !$opt[1]->isEmpty() ? $opt[1] : null);
    }, $opts));
  }
  
  private function validateClass(ParameterCollection $params, OptionCollection $opts) {
    //@TODO
  }
}
