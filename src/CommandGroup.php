<?php namespace BapCat\Console;

use BapCat\Propifier\PropifierTrait;
use BapCat\Values\ClassName;

class CommandGroup {
  use PropifierTrait;
  
  private $class;
  private $commands;
  
  public function __construct(ClassName $class, CommandCollection $commands) {
    $this->class    = $class;
    $this->commands = $commands;
  }
  
  public function __new(array $array) {
    
  }
  
  protected function getClass() {
    return $this->class;
  }
  
  protected function getCommands() {
    return $this->commands;
  }
}
