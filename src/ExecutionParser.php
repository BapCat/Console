<?php namespace BapCat\Console;

use BapCat\Interfaces\Ioc\Ioc;
use BapCat\Values\Boolean;
use BapCat\Values\ClassName;
use BapCat\Values\Regex;
use BapCat\Values\Text;

class ExecutionParser {
  private $ioc;
  private $command_registry;
  private $command_parser;
  
  private $regex_split;
  private $regex_param_long;
  private $regex_param_short;
  private $regex_opt_long;
  private $regex_opt_short;
  private $text_colon;
  
  public function __construct(Ioc $ioc, CommandRegistry $command_registry, CommandParser $command_parser) {
    $this->ioc = $ioc;
    
    $this->command_registry = $command_registry;
    $this->command_parser   = $command_parser;
    
    $this->regex_split = new Regex('#\h+#');
    
    $this->regex_param_long  = new Regex('#--(\w+)=(\w+)#');
    $this->regex_param_short = new Regex('#-(\w)=(\w+)#');
    $this->regex_opt_long    = new Regex('#--(\w+)#');
    $this->regex_opt_short   = new Regex('#-(\w)#');
    
    $this->text_colon = new Text(':');
  }
  
  public function parse(Text $command) {
    $parts = $this->regex_split->split($command);
    
    $group_name   = array_shift($parts);
    $command_name = null;
    
    if($group_name->contains($this->text_colon)) {
      $name_parts   = $group_name->split($this->text_colon);
      $group_name   = array_shift($name_parts);
      $command_name = array_shift($name_parts);
    }
    
    $group_binding = $this->command_registry->get((string)$group_name);
    $group_class   = new ClassName($this->ioc->resolve($group_binding));
    
    $group = $this->command_parser->parse($group_class);
    
    if($command_name === null) {
      $command_name = $group->commands->first()->name;
    } else {
      if(!$group->commands->has((string)$command_name)) {
        //@TODO
        die('Command not found in group');
      }
    }
    
    $command = $group->commands->get((string)$command_name);
    $params  = $command->parameters->all();
    $opts    = $command->options->all();
    
    $bindings = new ParameterBindingCollection();
    
    $unused = [];
    
    // Optional params
    while(count($parts) != 0) {
      $part = array_pop($parts);
      
      if($part->matches($this->regex_param_long)) {
        list($name, $val) = $this->regex_param_long->capture($part)[0];
        $param = $command->parameters->getByName($name);
        $value = $this->createValue($param->type, $val);
        $bindings->add(new ParameterBinding($param, $value));
        unset($params[(string)$param->name]);
      } elseif($part->matches($this->regex_param_short)) {
        list($name, $val) = $this->regex_param_short->capture($part)[0];
        $param = $command->parameters->getByShortName($name);
        $value = $this->createValue($param->type, $val);
        $bindings->add(new ParameterBinding($param, $value));
        unset($params[(string)$param->name]);
      } else {
        array_unshift($unused, $part);
      }
    }
    
    $parts = array_merge($parts, $unused);
    $unused = [];
    
    // Options
    while(count($parts) != 0) {
      $part = array_pop($parts);
      
      if($part->matches($this->regex_opt_long)) {
        $name = $this->regex_opt_long->capture($part)[0][0];
        $opt = $command->options->getByName($name);
        $bindings->add(new ParameterBinding($opt->toParameter(), new Boolean(true)));
        unset($opts[(string)$opt->name]);
      } elseif($part->matches($this->regex_opt_short)) {
        $name = $this->regex_opt_short->capture($part)[0][0];
        $opt = $command->options->getByShortName($name);
        $bindings->add(new ParameterBinding($opt->toParameter(), new Boolean(true)));
        unset($opts[(string)$opt->name]);
      } else {
        array_unshift($unused, $part);
      }
    }
    
    $parts = array_merge($parts, $unused);
    
    // Required params
    foreach($params as $param) {
      if(!$param->is_optional->raw) {
        $bindings->add(new ParameterBinding($param, array_shift($parts)));
      } else {
        $bindings->add(new ParameterBinding($param, null));
      }
    }
    
    foreach($opts as $opt) {
      $opt = $command->options->getByName($opt->name);
      $bindings->add(new ParameterBinding($opt->toParameter(), new Boolean(false)));
    }
    
    return $this->ioc->make(Execution::class, [$group_binding, $command, $bindings]);
  }
  
  private function createValue(ClassName $type, Text $value) {
    $class = $type->reflect();
    $value = $class->newInstance((string)$value);
    return $value;
  }
}
