<?php

use BapCat\Console\Command;
use BapCat\Values\Boolean;
use BapCat\Values\Text;
use BapCat\Values\PositiveInteger;

class StubCommand extends Command {
  /**
   * @param Text            $name   (short: n)  The name of the person to say hello to
   * @param PositiveInteger $count              The number of times to say hello
   * @opt                   $caps   (short: a)  Whether or not to display text in capitol letters
   */
  public function sayHello(Text $name, PositiveInteger $count, Boolean $caps) {
    for($i = 0; $i < $count->raw; $i++) {
      if($caps->raw) {
        echo "Hello $name";
      } else {
        echo "Hello {$name->toUpperCase()}";
      }
    }
  }
}
