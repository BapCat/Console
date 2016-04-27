<?php

use BapCat\Interfaces\Values\Value;

class Number extends Value {
  private $raw;
  
  public function __construct($value) {
    if(filter_var($value, FILTER_VALIDATE_INT) === false) {
      throw new InvalidArgumentException('Expected number, but got [' . var_export($value, true) . '] instead');
    }
    
    $this->raw = $value;
  }
  
  public function __toString() {
    return $this->raw;
  }
  
  public function jsonSerialize() {
    return $this->raw;
  }
  
  protected function getRaw() {
    return $this->raw;
  }
}
