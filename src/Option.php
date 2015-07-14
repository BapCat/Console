<?php namespace BapCat\Console;

use BapCat\Propifier\PropifierTrait;
use BapCat\Values\Boolean;
use BapCat\Values\ClassName;
use BapCat\Values\Text;

class Option {
  use PropifierTrait;
  
  private $name;
  private $description;
  private $short_name;
  
  public function __construct(Text $name, Text $description, Text $short_name = null) {
    $this->name        = $name;
    $this->description = $description;
    $this->short_name  = $short_name;
  }
  
  public function toParameter() {
    return new Parameter(new ClassName(Boolean::class), $this->name, $this->description, $this->short_name, new Boolean(false));
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
}
