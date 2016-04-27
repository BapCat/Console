<?php namespace BapCat\Console;

use BapCat\Collection\Interfaces\Collection;
use BapCat\Collection\Traits\ReadableCollectionTrait;

class CommandCollection implements Collection {
  use ReadableCollectionTrait;
  
  protected $collection = [];
  protected $lazy = [];
  
  public function __new(array $array) {
    return new CommandCollection();
  }
  
  public function add(Command $command) {
    $this->collection[(string)$command->name] = $command;
  }
}
