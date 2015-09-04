<?php

namespace Rougin\Describe;

use Rougin\Describe\DescribeInterface;
use Rougin\Describe\Column;

/**
 * Sqlite Class
 *
 * @package  Describe
 * @category Sqlite
 * @author   Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Sqlite implements DescribeInterface
{
	protected $database;
	protected $handle;

	/**
	 * Inject the database handle
	 * 
	 * @param \PDO   $handle
	 * @param string $database
	 */
	public function __construct($handle, $database)
	{
		$this->handle = $handle;
		$this->database = $database;
	}

	/**
	 * Return the result
	 * 
	 * @return array
	 */
	public function getInformationFromTable($table)
	{
		$columns = [];
		$query = 'PRAGMA table_info("' . $table . '");';

		$tableInformation = $this->handle->prepare($query);
		$tableInformation->execute();
		$tableInformation->setFetchMode(\PDO::FETCH_OBJ);

		if (strpos($table, '.')) {
			$table = substr($table, strpos($table, '.') + 1);
		}

		while ($row = $tableInformation->fetch()) {
			$column = new Column();

			if ( ! $row->notnull) {
				$column->setNull(TRUE);
			}

			if ($row->pk) {
				$column->setPrimary(TRUE);
				$column->setAutoIncrement(TRUE);
			}

			$column->setDefaultValue($row->dflt_value);
			$column->setField($row->name);
			$column->setDataType(strtolower($row->type));

			$columns[] = $column;
		}

		return $columns;
	}

	/**
	 * Show the list of tables
	 * 
	 * @return array
	 */
	public function showTables()
	{
		$tables = [];

		return $tables;
	}
}
