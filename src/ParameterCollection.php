<?php namespace BapCat\Console;

use BapCat\Collection\Interfaces\Collection;
use BapCat\Collection\Traits\ReadableCollectionTrait;
use BapCat\Values\Text;

class ParameterCollection implements Collection {
  use ReadableCollectionTrait;
  
  protected $collection = [];
  
  public function __construct(array $params = []) {
    foreach($params as $param) {
      $this->collection[(string)$param->name] = $param;
    }
  }
  
  public function __new(array $params = []) {
    return new ParameterCollection($params);
  }
  
  public function add(Parameter $param) {
    $this->collection[(string)$param->name] = $param;
  }
  
  public function getByName(Text $name) {
    return $this->collection[(string)$name];
  }
  
  public function getByShortName(Text $name) {
    foreach($this->collection as $param) {
      if($name->equals($param->short_name)) {
        return $param;
      }
    }
  }
}
