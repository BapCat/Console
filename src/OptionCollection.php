<?php namespace BapCat\Console;

use BapCat\Values\Text;

class OptionCollection {
  private $opts = [];
  
  public function __construct(array $opts = []) {
    foreach($opts as $opt) {
      $this->opts[(string)$opt->name] = $opt;
    }
  }
  
  public function add(Option $opt) {
    $this->opts[(string)$opt->name] = $opt;
  }
  
  public function getByName(Text $name) {
    return $this->opts[(string)$name];
  }
  
  public function getByShortName(Text $name) {
    foreach($this->opts as $opt) {
      if($name->equals($opt->short_name)) {
        return $opt;
      }
    }
  }
}
