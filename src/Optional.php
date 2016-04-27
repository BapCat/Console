<?php namespace BapCat\Console;

use BapCat\Interfaces\Values\Value;

use InvalidArgumentException;

class Optional extends Value {
  private static $VALID_TRUE  = [true,  1, '1'];
  private static $VALID_FALSE = [false, 0, '0'];
  
  private $raw;
  
  public function __construct($value) {
    if(in_array($value, static::$VALID_TRUE, true)) {
      $this->raw = true;
    }
    
    if(in_array($value, static::$VALID_FALSE, true)) {
      $this->raw = false;
    }
    
    if($this->raw === null) {
      throw new InvalidArgumentException('Expected true or false, but got [' . var_export($value, true) . '] instead');
    }
  }
  
  public function __toString() {
    return $this->raw ? 'true' : 'false';
  }
  
  public function jsonSerialize() {
    return $this->raw;
  }
  
  protected function getRaw() {
    return $this->raw;
  }
}
