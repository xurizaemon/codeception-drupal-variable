<?php

namespace Codeception\Module\DrupalVariable\VariableStorage;

/**
 * Read/Write variables to/from a drupal db directly. Requires mysql connection.
 *
 * @todo does not support table prefixes.
 */
class DirectConnection implements StorageInterface
{
    /**
     * @var PDO
     */
    protected $dbh;

    /**
     * Constructor. Receive and store module config.
     *
     * @param array $config
     *   Config array.
     */
    public function __construct($config)
    {
        $this->config = $config;

        if (empty($this->config['dsn'])) {
          throw new \InvalidArgumentException("Config for DirectConnection should contain dsn.");
        }
    }

    /**
     * Get the db connection.
     *
     * @return PDO
     */
    public function getDbh()
    {
        if (!isset($this->dbh)) {
            $this->dbh = new \PDO($this->config['dsn'], $this->config['user'], $this->config['password'], array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ));
        }

        return $this->dbh;
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
        $this->getDbh()->prepare("replace into variable (name,value) values (:name, :value)")->execute(array(
            ":name" => $name,
            ":value" => serialize($value),
          ));
    }

    /**
     * Read a drupal variable.
     *
     * @param string $name
     *   The variable name.
     *
     * @return bool|mixed
     *   false on failure, the variable on success.
     */
    public function readVariable($name, $default = null)
    {
        $stmt = $this->getDbh()->prepare("select value from variable where name = :name");
        $stmt->execute(array(
            ":name" => $name,
          ));

        if (!$val = $stmt->fetchColumn()) {
            return $default;
        }

        return unserialize($val);
    }

    /**
     * Remove a drupal variable.
     *
     * @param string $name
     *   The variable name.
     */
    public function deleteVariable($name)
    {
        $this->getDbh()->prepare("delete from variable where name = :name")->execute(array(
              ":name" => $name,
        ));
    }
}
