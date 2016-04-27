<?php namespace BapCat\Console;

use BapCat\Propifier\PropifierTrait;
use BapCat\Values\ClassName;
use BapCat\Values\Text;

class Parameter {
  use PropifierTrait;
  
  private $type;
  private $name;
  private $description;
  private $short_name;
  private $is_optional;
  
  public function __construct(ClassName $type, Text $name, Text $description, Text $short_name = null, $is_optional = false) {
    $this->type        = $type;
    $this->name        = $name;
    $this->description = $description;
    $this->short_name  = $short_name;
    $this->is_optional = $is_optional;
  }
  
  protected function getType() {
    return $this->type;
  }
  
  protected function getName() {
    return $this->name;
  }
  
  protected function getDescription() {
    return $this->description;
  }
  
  protected function getShortName() {
    return $this->short_name;
  }
  
  protected function getIsOptional() {
    return $this->is_optional;
  }
}
