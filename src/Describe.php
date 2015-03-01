<?php namespace Describe;

use Describe\MySql;

class Describe {

	private $_columns        = array();
	private $_databaseHandle = NULL;
	private $_databaseDriver = NULL;

	/**
	 * Get the properties and attributes of the specified table
	 * 
	 * @param string $hostname
	 * @param string $database
	 * @param string $username
	 * @param string $password
	 * @param string $driver
	 */
	public function __construct($hostname, $database = NULL, $username = NULL, $password = NULL, $driver = NULL)
	{
		if (is_array($hostname)) {
			$database = $hostname['database'];
			$driver   = $hostname['driver'];
			$password = $hostname['password'];
			$username = $hostname['username'];

			$hostname = $hostname['hostname'];
		}

		/**
		 * Change to "mysql" if the driver specified is "mysqli"
		 */

		$driver = ($driver == 'mysqli') ? 'mysql' : $driver;

		/**
		 * Set as the currently selected driver
		 */

		$this->_databaseDriver = $driver;

		/**
		 * Connect to the database
		 */

		try {
			$this->_databaseHandle = new \PDO(
				$driver .
				':host=' . $hostname .
				';dbname=' . $database,
				$username,
				$password
			);

			$this->_databaseHandle->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
		catch (\PDOException $error) {
			exit($error->getMessage());
		}
	}

	/**
	 * Return the result
	 * 
	 * @return array
	 */
	public function getInformationFromTable($table)
	{
		switch ($this->_databaseDriver) {
			case 'mysql':
				$mysql = new MySql($this->_databaseHandle);
				$this->_columns = $mysql->getInformationFromTable($table);

				break;
			default:
				break;
		}

		return $this->_columns;
	}

	/**
	 * Get the primary key in the specified table
	 * 
	 * @param  string $table
	 * @return string
	 */
	public function getPrimaryKey($table)
	{
		$columns = $this->_columns;

		if (empty($columns)) {
			$columns = $this->getInformationFromTable($table);
		}

		foreach ($columns as $column) {
			if ($column->key == 'PRI') {
				return $column->field;
			}
		}
	}

}