<?php

//@NOTE THIS TEST IS GARBAGE

require_once __DIR__ . '/stubs/StubCommand.php';

use BapCat\Console\CommandParser;
use BapCat\Console\CommandRegistry;
use BapCat\Console\Executable;
use BapCat\Console\ExecutionParser;
use BapCat\Console\Parameter;
use BapCat\Console\ParameterCollection;
use BapCat\Console\Option;
use BapCat\Console\OptionCollection;
use BapCat\Interfaces\Ioc\Ioc;
use BapCat\Phi\Phi;
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
    $ioc = Phi::instance();
    
    $registry = $ioc->make(CommandRegistry::class);
    $registry->register('hello', StubCommand::class);
    
    $parser = $ioc->make(CommandParser::class);
    
    $ioc->bind(Ioc::class, $ioc);
    $ioc->bind(CommandRegistry::class, $registry);
    $ioc->bind(CommandParser::class, $parser);
    
    $this->command_parser   = $parser;
    $this->execution_parser = $ioc->make(ExecutionParser::class);
    
    /*$this->expected_params = new ParameterCollection([
      new Parameter(new ClassName(Text::class),          new Text('name'),  new Text('The name of the person to say hello to'), new Text('n')),
      new Parameter(new ClassName(PositiveInteger::class), new Text('count'), new Text('The number of times to say hello'))
    ]);
    
    $this->expected_opts = new OptionCollection([
      new Option(new Text('caps'), new Text('Whether or not to display text in capitol letters'), new Text('a'))
    ]);*/
    
    $this->class = new ClassName(StubCommand::class);
  }
  
  public function testParseCommand() {
    $commands = $this->command_parser->parse($this->class);
    
    //$this->assertEquals($this->expected_params, $executable->parameters);
    //$this->assertEquals($this->expected_opts,   $executable->options);
  }
  
  public function testParseExecution() {
    //$command = new Text('hello:say Corey --count=10 -a');
    $command = new Text('hello Corey');
    
    $execution = $this->execution_parser->parse($command);
    $execution->execute();
    
    //var_dump($execution);
  }
}
