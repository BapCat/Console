<?php namespace BapCat\Console;

use BapCat\Collection\ReadOnlyCollection;

class CommandCollection extends ReadOnlyCollection {
  protected $collection = [];
  
  public function add(Command $command) {
    $this->collection[(string)$command->name] = $command;
  }
}
