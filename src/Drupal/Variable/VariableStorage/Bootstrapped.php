<?php

namespace Codeception\Module\Drupal\Variable\VariableStorage;

/**
 * Read/Write variables using a bootrapped drupal instance.
 */
class Bootstrapped implements StorageInterface
{
    /**
     * Constructor. Receive and store module config.
     *
     * @param $config
     *
     * @throws Codeception\Exception\ModuleConfig
     *   If drupal_root is not set/accessible.
     */
    public function __construct($config)
    {
        $this->config = $config;
        if (!$this->config['enabled']) {
            return;
        }

        if (empty($this->config['drupal_root']) || !file_exists($this->config['drupal_root'])) {
            throw new \Codeception\Exception\ModuleConfig("VariableStorageBootstrapped", "drupal_root not set.");
        }

        $thisdir = getcwd();

        $_SERVER['REMOTE_ADDR'] = "";
        chdir($this->config['drupal_root']);
        define('DRUPAL_ROOT', $this->config['drupal_root']);
        require_once $this->config['drupal_root'] . "/includes/bootstrap.inc";
        drupal_bootstrap(DRUPAL_BOOTSTRAP_VARIABLES);

        chdir($thisdir);
    }

    /**
     * Write a drupal variable.
     *
     * @param string $name
     *   The variable name.
     * @param mixed $value
     *   The variable value (not serialized).
     */
    public function writeVariable($name, $value)
    {
        variable_set($name, $value);
    }

    /**
     * Read a drupal variable.
     *
     * @param string $name
     *   The variable name.
     * @param null $default
     *   Pass in a default to use of the variable doesn't exist.
     *
     * @return bool|mixed
     *   false on failure, the variable on success.
     */
    public function readVariable($name, $default = null)
    {
        global $conf;
        $conf = variable_initialize($conf);

        return variable_get($name, $default);
    }

    /**
     * Remove a drupal variable.
     *
     * @param string $name
     *   The variable name.
     */
    public function deleteVariable($name)
    {
        return variable_del($name);
    }

    /**
     * @return array
     */
    public static function getRequiredFields()
    {
        return array("drupal_root");
    }
}
