<?php

namespace Codeception\Module\Drupal\Variable\VariableStorage;

/**
 * Interface for reading/writing drupal variables.
 */
interface StorageInterface
{
    /**
     * Read a variable from drupal.
     *
     * @param string $name
     *   The variable name.
     * @param mixed $default
     *   The return value if no value is set.
     * @return mixed
     *   The variable value.
     */
    public function readVariable($name, $default = null);

    /**
     * Write a variable to drupal.
     *
     * @param string $name
     *   The variable name.
     * @param $value
     *   The value to write, not serialized.
     */
    public function writeVariable($name, $value);

    /**
     * Remove a variable from drupal.
     *
     * @param string $name
     *   The variable name.
     */
    public function deleteVariable($name);

    /**
     * Return an array of field names required in config.
     *
     * @return array
     *   Array of field names e.g. array('dsn','drupal_root')
     */
    public static function getRequiredFields();
}
