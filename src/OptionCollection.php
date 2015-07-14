<?php namespace BapCat\Console;

use BapCat\Collection\Interfaces\Collection;
use BapCat\Collection\Traits\ReadableCollectionTrait;
use BapCat\Values\Text;

class OptionCollection implements Collection {
  use ReadableCollectionTrait;
  
  protected $collection = [];
  
  public function __construct(array $opts = []) {
    foreach($opts as $opt) {
      $this->collection[(string)$opt->name] = $opt;
    }
  }
  
  public function __new(array $opts = []) {
    return new OptionCollection($opts);
  }
  
  public function add(Option $opt) {
    $this->collection[(string)$opt->name] = $opt;
  }
  
  public function getByName(Text $name) {
    return $this->collection[(string)$name];
  }
  
  public function getByShortName(Text $name) {
    foreach($this->collection as $opt) {
      if($name->equals($opt->short_name)) {
        return $opt;
      }
    }
  }
}
