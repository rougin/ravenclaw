<?php

namespace Rougin\Describe\Drivers;

use PDO;

/**
 * Database Driver
 *
 * A database driver for using available database drivers.
 *
 * @package  Describe
 * @category Drivers
 * @author   Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DatabaseDriver implements DriverInterface
{
    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * @var string
     */
    protected $driver = '';

    /**
     * @param string $driver
     * @param array  $configuration
     */
    public function __construct($driver, $configuration = [])
    {
        $this->driver = $driver;
        $this->configuration = $configuration;
    }

    /**
     * Gets the specified driver from the specified database connection.
     *
     * @param string $driverName
     * @param array  $configuration
     * @return \Rougin\Describe\Driver\DriverInterface|null
     */
    public function getDriver($driverName, $configuration = [])
    {
        $driver   = null;
        $database = null;
        $hostname = null;
        $password = null;
        $username = null;

        if (isset($configuration['database'])) {
            $database = $configuration['database'];
        }

        if (isset($configuration['hostname'])) {
            $hostname = $configuration['hostname'];
        }

        if (isset($configuration['password'])) {
            $password = $configuration['password'];
        }

        if (isset($configuration['username'])) {
            $username = $configuration['username'];
        }

        switch ($driverName) {
            case 'mysql':
            case 'mysqli':
                $dsn    = 'mysql:host=' . $hostname . ';dbname=' . $database;
                $pdo    = new PDO($dsn, $username, $password);
                $driver = new MySQLDriver($pdo, $database);

                break;
            case 'pdo':
            case 'sqlite':
            case 'sqlite3':
                $pdo    = new PDO($hostname);
                $driver = new SQLiteDriver($pdo);

                break;
        }

        return $driver;
    }

    /**
     * Returns the result.
     *
     * @return array
     */
    public function getTable($table)
    {
        $driver = $this->getDriver($this->driver, $this->configuration);

        return $driver->getTable($table);
    }

    /**
     * Shows the list of tables.
     *
     * @return array
     */
    public function showTables()
    {
        $driver = $this->getDriver($this->driver, $this->configuration);

        return $driver->showTables();
    }
}
