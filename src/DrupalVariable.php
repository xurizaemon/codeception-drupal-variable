<?php
namespace Codeception\Module;

use Codeception\Module\DrupalVariable\VariableStorage\StorageInterface;
use Codeception\TestCase;

class DrupalVariable extends \Codeception\Module
{
    /**
     * @var array
     *   List of required config fields.
     */
    protected $requiredFields = array('class');

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

        // TODO: could I inject this in somehow?
        $this->variableStorage = new $class($this->config);
        $this->requiredFields = array_merge(
            $this->requiredFields,
            $this->variableStorage->getRequiredFields()
        );
        $this->validateConfig();
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

        $interface = "Codeception\\Module\\Drupal\\Variable\\VariableStorage\\StorageInterface";
        if (!in_array($interface, class_implements($class))) {
            throw new \Codeception\Exception\ModuleConfig(
                "DrupalVariable",
                "Invalid config. Class '$class' must implement '$interface'"
            );
        }
    }

    /**
     * Set a variable.
     *
     * @param string $name
     *   The variable name.
     * @param mixed $value
     *   The variable value, not serialized.
     */
    public function haveVariable($name, $value)
    {
        $this->variableStorage->writeVariable($name, $value);
    }

    /**
     * Delete a variable.
     *
     * @param string $name
     *   The variable to delete.
     */
    public function dontHaveVariable($name)
    {
        $this->variableStorage->deleteVariable($name);
    }

    /**
     * See variable matches expected value.
     *
     * @param string $name
     *   The variable name.
     * @param mixed $expectedValue
     *   The expected value.
     */
    public function seeVariable($name, $expectedValue)
    {
        $this->assertEquals($expectedValue, $this->variableStorage->readVariable($name));
    }

    /**
     * See variable does not match a value.
     *
     * @param string $name
     *   The variable name.
     * @param $expectedValue
     *   The expectedValue that should not be matched.
     */
    public function dontSeeVariable($name, $expectedValue)
    {
        $this->assertNotEquals($expectedValue, $this->variableStorage->readVariable($name));
    }

    /**
     * Get the variable value.
     *
     * @param string $name
     *   The variable name.
     * @return mixed
     *   The variable value, not serialized.
     */
    public function getVariable($name)
    {
        return $this->variableStorage->readVariable($name);
    }
}
