<?php namespace BapCat\Console;

class Execution {
  private $executable;
  private $bindings;
  private $options;
  
  public function __construct(Executable $executable, ParameterBindingCollection $bindings, OptionCollection $options) {
    $this->executable = $executable;
    $this->bindings   = $bindings;
    $this->options    = $options;
  }
}
