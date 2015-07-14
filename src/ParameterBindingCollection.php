<?php namespace BapCat\Console;

use BapCat\Collection\Interfaces\Collection;
use BapCat\Collection\Traits\ReadableCollectionTrait;
use BapCat\Values\Text;

class ParameterBindingCollection {
  use ReadableCollectionTrait;
  
  protected $collection = [];
  
  public function __construct(array $bindings = []) {
    foreach($bindings as $binding) {
      $this->collection[(string)$binding->name] = $binding;
    }
  }
  
  public function add(ParameterBinding $binding) {
    $this->collection[(string)$binding->parameter->name] = $binding;
  }
  
  public function getByName(Text $name) {
    return $this->collection[(string)$name];
  }
}
