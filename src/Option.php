<?php namespace BapCat\Console;

use BapCat\Propifier\PropifierTrait;
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
