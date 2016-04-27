<?php

include __DIR__ . '/Number.php';

use BapCat\Console\Optional;
use BapCat\Values\Text;

class StubCommand {
  /**
   * @param  Text    $name               The name of the person to say hello to
   * @param  Number  $count              The number of times to say hello
   * @opt            $caps   (short: a)  Whether or not to display text in capitol letters
   */
  public function say(Text $name, Number $count = null, Optional $caps) {
    if($count === null) {
      $count = new Number(1);
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
