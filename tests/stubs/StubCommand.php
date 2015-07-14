<?php

use BapCat\Values\Boolean;
use BapCat\Values\Text;
use BapCat\Values\PositiveInteger;

class StubCommand {
  /**
   * @param Text            $name   (short: n)  The name of the person to say hello to
   * @param PositiveInteger $count              The number of times to say hello
   * @opt                   $caps   (short: a)  Whether or not to display text in capitol letters
   */
  public function say(Text $name, PositiveInteger $count = null, Boolean $caps = null) {
    if($count === null) {
      $count = new PositiveInteger(1);
    }
    
    for($i = 0; $i < $count->raw; $i++) {
      if(!$caps->raw) {
        echo "Hello $name\n";
      } else {
        echo "HELLO {$name->toUpperCase()}\n";
      }
    }
  }
}
