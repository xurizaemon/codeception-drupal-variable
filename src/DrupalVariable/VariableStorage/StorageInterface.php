<?php

namespace Codeception\Module\DrupalVariable\VariableStorage;

/**
 * Interface for reading/writing drupal variables.
 */
interface StorageInterface
{
    public function readVariable($name, $default = null);
    public function writeVariable($name, $value);
    public function deleteVariable($name);
}
