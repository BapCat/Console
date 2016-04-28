<?php namespace BapCat\Console;

class CommandRegistry {
  private $commands = [];
  
  public function register($alias, $ioc_binding) {
    $this->commands[$alias] = $ioc_binding;
  }
  
  public function get($alias) {
    if(!isset($this->commands[$alias])) {
      throw new NoSuchCommandException($alias);
    }
    
    return $this->commands[$alias];
  }
}
