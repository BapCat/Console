<?php namespace BapCat\Console;

class CommandRegistry {
  private $commands = [];
  
  public function register($command_alias, $ioc_binding) {
    $this->commands[$command_alias] = $ioc_binding;
  }
  
  public function get($command_alias) {
    return $this->commands[$command_alias];
  }
}
