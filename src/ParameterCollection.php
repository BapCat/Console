<?php namespace BapCat\Console;

use BapCat\Values\Text;

class ParameterCollection {
  private $params = [];
  
  public function __construct(array $params = []) {
    foreach($params as $param) {
      $this->params[(string)$param->name] = $param;
    }
  }
  
  public function add(Parameter $param) {
    $this->params[(string)$param->name] = $param;
  }
  
  public function getByName(Text $name) {
    return $this->params[(string)$name];
  }
  
  public function getByShortName(Text $name) {
    foreach($this->params as $param) {
      if($name->equals($param->short_name)) {
        return $param;
      }
    }
  }
}
