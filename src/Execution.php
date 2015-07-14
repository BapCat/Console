<?php namespace BapCat\Console;

use BapCat\Interfaces\Ioc\Ioc;
use BapCat\Values\Boolean;

class Execution {
  private $ioc;
  
  private $binding;
  private $command;
  private $bindings;
  private $options;
  
  public function __construct(Ioc $ioc, $binding, Command $command, ParameterBindingCollection $bindings, OptionCollection $options) {
    $this->ioc = $ioc;
    
    $this->binding  = $binding;
    $this->command  = $command;
    $this->bindings = $bindings;
    $this->options  = $options;
  }
  
  public function execute() {
    $args = [];
    
    $this->bindings->each(function($param, $key) use(&$args) {
      $args[$key] = $param->value;
    });
    
    $this->options->each(function($opt, $key) use(&$args) {
      $args[$key] = new Boolean(true);
    });
    
    $instance = $this->ioc->make($this->binding);
    
    $command_name = (string)$this->command->name;
    return $this->ioc->execute($instance, $command_name, $args);
  }
}
