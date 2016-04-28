<?php namespace BapCat\Console;

use Exception;

class NoSuchCommandException extends Exception {
  public function __construct($name) {
    parent::__construct("There is no command by the name of $name");
  }
}
