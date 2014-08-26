<?php
namespace Codeception\Module;

use Codeception\Module\DrupalVariable\VariableStorage\StorageInterface;
use Codeception\TestCase;

class DrupalVariable extends \Codeception\Module
{
  /**
   * @var StorageInterface
   */
  protected $variableStorage;
  
  /**
   * Create the variable storage class to read/write drupal variables.
   */
  public function _initialize()
  {
    $class = $this->config['class'];
    $this->variableStorage = new $class($this->config);
  }

  /**
   * Ensure the class specified is valid.
   *
   * @see \Codeception\Module::validateConfig()
   * @throws \Codeception\Exception\ModuleConfig
   */
  protected function validateConfig()
  {
    parent::validateConfig();

    $class = $this->config['class'];
    if (!class_exists($class)) {
      throw new \Codeception\Exception\ModuleConfig(
        "DrupalVariable",
        "Invalid config. Class '$class' does not exist"
      );
    }

    $interface = "Codeception\\Module\\DrupalVariable\\VariableStorage\\StorageInterface";
    if (!in_array($interface, class_implements($class))) {
      throw new \Codeception\Exception\ModuleConfig(
        "DrupalVariable",
        "Invalid config. Class '$class' must implement '$interface'"
      );
    }
  }

  public function haveVariable($name, $value)
  {
    $this->variableStorage->writeVariable($name, $value);
  }

  public function dontHaveVariable($name)
  {
    $this->variableStorage->deleteVariable($name);
  }

  public function seeVariable($name, $expectedValue)
  {
    $this->assertEquals($expectedValue, $this->variableStorage->readVariable($name));
  }

  public function dontSeeVariable($name, $expectedValue)
  {
    $this->assertNotEquals($expectedValue, $this->variableStorage->readVariable($name));
  }

  public function getVariable($name)
  {
    return $this->variableStorage->readVariable($name);
  }
}
