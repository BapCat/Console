<?php namespace BapCat\Console;

use BapCat\Values\Text;

class ParameterBindingCollection {
  private $bindings = [];
  
  public function __construct(array $bindings = []) {
    foreach($bindings as $binding) {
      $this->bindings[(string)$binding->name] = $binding;
    }
  }
  
  public function add(ParameterBinding $binding) {
    $this->bindings[(string)$binding->parameter->name] = $binding;
  }
  
  public function getByName(Text $name) {
    return $this->bindings[(string)$name];
  }
}
