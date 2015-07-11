<?php namespace BapCat\Console;

use BapCat\Interfaces\Values\Value;
use BapCat\Propifier\PropifierTrait;

class ParameterBinding {
  use PropifierTrait;
  
  private $param;
  private $value;
  
  public function __construct(Parameter $param, Value $value) {
    $this->param = $param;
    $this->value = $value;
  }
  
  protected function getParameter() {
    return $this->param;
  }
  
  protected function getValue() {
    return $this->value;
  }
}
