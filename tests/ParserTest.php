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
use BapCat\Phi\Phi;
use BapCat\Values\ClassName;
use BapCat\Values\Text;

class ParserTest extends PHPUnit_Framework_TestCase {
  private $command_parser;
  private $execution_parser;
  private $commands;
  private $expected_params;
  private $expected_opts;
  
  public function setUp() {
    $ioc = Phi::instance();
    
    $registry = $ioc->make(CommandRegistry::class);
    $registry->register('hello', StubCommand::class);
    
    $parser = $ioc->make(CommandParser::class);
    
    $ioc->bind(CommandRegistry::class, $registry);
    $ioc->bind(CommandParser::class, $parser);
    
    $this->command_parser   = $parser;
    $this->execution_parser = $ioc->make(ExecutionParser::class);
    
    $this->expected_params = new ParameterCollection([
      new Parameter(new ClassName(Text::class),   new Text('name'),  new Text('The name of the person to say hello to')),
      new Parameter(new ClassName(Number::class), new Text('count'), new Text('The number of times to say hello'), null, true)
    ]);
    
    $this->expected_opts = new OptionCollection([
      new Option(new Text('caps'), new Text('Whether or not to display text in capitol letters'), new Text('a'))
    ]);
    
    $this->commands = new ClassName(StubCommand::class);
  }
  
  public function testParseCommand() {
    $commands = $this->command_parser->parse($this->commands);
    
    $this->assertSame(1, $commands->commands->size());
    
    $command = $commands->commands->first();
    
    $this->assertEquals($this->expected_params, $command->parameters);
    $this->assertEquals($this->expected_opts,   $command->options);
  }
  
  public function testParseExecution() {
    $command = new Text('hello:say Corey --count=10 -a');
    
    $executable = $this->execution_parser->parse($command);
    $executable->execute();
    
    //var_dump($executable);
  }
}
