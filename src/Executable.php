<?php namespace BapCat\Console;

use BapCat\Propifier\PropifierTrait;
use BapCat\Values\ClassName;

class Executable {
  use PropifierTrait;
  
  private $class;
  private $params;
  private $opts;
  
  public function __construct(ClassName $class, ParameterCollection $params, OptionCollection $opts) {
    $this->class  = $class;
    $this->params = $params;
    $this->opts   = $opts;
  }
  
  protected function getClass() {
    return $this->class;
  }
  
  protected function getParameters() {
    return $this->params;
  }
  
  protected function getOptions() {
    return $this->opts;
  }
}
