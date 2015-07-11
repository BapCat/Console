<?php

//@NOTE THIS TEST IS GARBAGE

require_once __DIR__ . '/stubs/StubCommand.php';

use BapCat\Console\CommandParser;
use BapCat\Console\Executable;
use BapCat\Console\ExecutionParser;
use BapCat\Console\Parameter;
use BapCat\Console\ParameterCollection;
use BapCat\Console\Option;
use BapCat\Console\OptionCollection;
use BapCat\Values\ClassName;
use BapCat\Values\PositiveInteger;
use BapCat\Values\Text;

class ParserTest extends PHPUnit_Framework_TestCase {
  private $command_parser;
  private $execution_parser;
  private $class;
  private $expected_params;
  private $expected_opts;
  
  public function setUp() {
    $this->command_parser   = new CommandParser();
    $this->execution_parser = new ExecutionParser();
    
    $this->expected_params = new ParameterCollection([
      new Parameter(new ClassName(Text::class),          new Text('name'),  new Text('The name of the person to say hello to'), new Text('n')),
      new Parameter(new ClassName(PositiveInteger::class), new Text('count'), new Text('The number of times to say hello'))
    ]);
    
    $this->expected_opts = new OptionCollection([
      new Option(new Text('caps'), new Text('Whether or not to display text in capitol letters'), new Text('a'))
    ]);
    
    $this->class = new ClassName(StubCommand::class);
  }
  
  public function testParseCommand() {
    $executable = $this->command_parser->parse($this->class);
    
    $this->assertEquals($this->expected_params, $executable->parameters);
    $this->assertEquals($this->expected_opts,   $executable->options);
  }
  
  public function testParseExecution() {
    $command = new Text('test -n=Corey --count=10 -a');
    
    $executable = new Executable($this->class, $this->expected_params, $this->expected_opts);
    
    $execution = $this->execution_parser->parse($command, $executable);
    
    var_dump($execution);
  }
}
