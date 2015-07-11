<?php namespace BapCat\Console;

use BapCat\Values\ClassName;
use BapCat\Values\Regex;
use BapCat\Values\Text;

class ExecutionParser {
  private $regex_split;
  private $regex_param_long;
  private $regex_param_short;
  private $regex_opt_long;
  private $regex_opt_short;
  
  public function __construct() {
    $this->regex_split = new Regex('#\h+#');
    
    $this->regex_param_long  = new Regex('#--(\w+)=(\w+)#');
    $this->regex_param_short = new Regex('#-(\w)=(\w+)#');
    $this->regex_opt_long    = new Regex('#--(\w+)#');
    $this->regex_opt_short   = new Regex('#-(\w)#');
  }
  
  public function parse(Text $command, Executable $executable) {
    $parts = $this->regex_split->split($command);
    
    $name = array_shift($parts);
    
    $bindings = new ParameterBindingCollection();
    $options  = new OptionCollection();
    
    foreach($parts as $part) {
      if($part->matches($this->regex_param_long)) {
        list($name, $val) = $this->regex_param_long->capture($part)[0];
        $param = $executable->parameters->getByName($name);
        $value = $this->createValue($param->type, $val);
        $bindings->add(new ParameterBinding($param, $value));
      } elseif($part->matches($this->regex_param_short)) {
        list($name, $val) = $this->regex_param_short->capture($part)[0];
        $param = $executable->parameters->getByShortName($name);
        $value = $this->createValue($param->type, $val);
        $bindings->add(new ParameterBinding($param, $value));
      } elseif($part->matches($this->regex_opt_long)) {
        $name = $this->regex_opt_long->capture($part)[0][0];
        $options->add($executable->options->getByName($name));
      } elseif($part->matches($this->regex_opt_short)) {
        $name = $this->regex_opt_short->capture($part)[0][0];
        $options->add($executable->options->getByShortName($name));
      }
    }
    
    return new Execution($executable, $bindings, $options);
  }
  
  private function createValue(ClassName $type, Text $value) {
    $class = $type->reflect();
    $value = $class->newInstance((string)$value);
    return $value;
  }
}
