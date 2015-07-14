<?php namespace BapCat\Console;

use BapCat\Propifier\PropifierTrait;
use BapCat\Values\Text;

class Command {
  use PropifierTrait;
  
  private $name;
  private $params;
  private $opts;
  
  public function __construct(Text $name, ParameterCollection $params, OptionCollection $opts) {
    $this->name   = $name;
    $this->params = $params;
    $this->opts   = $opts;
  }
  
  protected function getName() {
    return $this->name;
  }
  
  protected function getParameters() {
    return $this->params;
  }
  
  protected function getOptions() {
    return $this->opts;
  }
  
  protected function getHelp() {
    $out  = "Command: {$this->name}\n\n";
    $out .= "Required Parameters:\n";
    
    $col1 = [];
    $col2 = [];
    
    $this->params->each(function(Parameter $param, $name) use(&$col1, &$col2) {
      if(!$param->is_optional->raw) {
        $left = " --{$param->name}";
        
        if($param->short_name !== null) {
          $left .= " (-{$param->short_name})";
        }
        
        $right = "{$param->description}";
        
        $col1[] = $left;
        $col2[] = " $right";
      }
    });
    
    $length = 0;
    
    foreach($col1 as $text) {
      if(strlen($text) > $length) {
        $length = strlen($text);
      }
    }
    
    for($i = 0; $i < count($col1); $i++) {
      $out .= str_pad($col1[$i], $length) . " {$col2[$i]}\n";
    }
    
    $out .= "\n";
    $out .= "Optional Parameters:\n";
    
    $col1 = [];
    $col2 = [];
    
    $this->params->each(function(Parameter $param, $name) use(&$col1, &$col2) {
      if($param->is_optional->raw) {
        $left = " --{$param->name}";
        
        if($param->short_name !== null) {
          $left .= " (-{$param->short_name})";
        }
        
        $right = "{$param->description}";
        
        $col1[] = $left;
        $col2[] = " $right";
    }
    });
    
    $length = 0;
    
    foreach($col1 as $text) {
      if(strlen($text) > $length) {
        $length = strlen($text);
      }
    }
    
    for($i = 0; $i < count($col1); $i++) {
      $out .= str_pad($col1[$i], $length) . " {$col2[$i]}\n";
    }
    
    $out .= "\n";
    $out .= "Options:\n";
    
    $col1 = [];
    $col2 = [];
    
    $this->opts->each(function(Option $opt, $name) use(&$col1, &$col2) {
      $left = " --{$opt->name}";
      
      if($opt->short_name !== null) {
        $left .= " (-{$opt->short_name})";
      }
      
      $col1[] = $left;
      $col2[] = " {$opt->description}";
    });
    
    $length = 0;
    
    foreach($col1 as $text) {
      if(strlen($text) > $length) {
        $length = strlen($text);
      }
    }
    
    for($i = 0; $i < count($col1); $i++) {
      $out .= str_pad($col1[$i], $length) . " {$col2[$i]}\n";
    }
    
    return $out;
  }
}
