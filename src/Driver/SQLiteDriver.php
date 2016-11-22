<?php

namespace Rougin\Describe\Driver;

use Rougin\Describe\Column;

/**
 * SQLite Driver
 *
 * A database driver extension for SQLite.
 *
 * @package  Describe
 * @category Driver
 * @author   Rougin Royce Gutib <rougingutib@gmail.com>
 */
class SQLiteDriver implements DriverInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Returns the result.
     *
     * @return array
     */
    public function getTable($table)
    {
        $columns = [];

        // Gets list of columns
        $query = 'PRAGMA table_info("' . $table . '");';
        $information = $this->pdo->prepare($query);

        $information->execute();
        $information->setFetchMode(\PDO::FETCH_OBJ);

        while ($row = $information->fetch()) {
            $column = new Column;

            $this->setProperties($row, $column);

            $column->setDefaultValue($row->dflt_value);
            $column->setField($row->name);
            $column->setDataType(strtolower($row->type));

            array_push($columns, $column);
        }

        return $this->prepareForeignColumns($table, $columns);
    }

    /**
     * Shows the list of tables.
     *
     * @return array
     */
    public function showTables()
    {
        $tables = [];

        // Gets list of columns
        $query = 'SELECT name FROM sqlite_master WHERE type = "table";';
        $information = $this->pdo->prepare($query);

        $information->execute();
        $information->setFetchMode(\PDO::FETCH_OBJ);

        while ($row = $information->fetch()) {
            if ($row->name != 'sqlite_sequence') {
                array_push($tables, $row->name);
            }
        }

        return $tables;
    }

    /**
     * Prepares the columns that have foreign keys.
     *
     * @param  string $tableName
     * @param  array  $columns
     * @return void
     */
    protected function prepareForeignColumns($tableName, array $columns)
    {
        // Gets list of foreign keys, if any
        $query = 'PRAGMA foreign_key_list("' . $tableName . '");';
        $table = $this->pdo->prepare($query);

        $table->execute();
        $table->setFetchMode(\PDO::FETCH_OBJ);

        $rows = [];

        while ($row = $table->fetch()) {
            array_push($rows, $row);
        }

        return $this->setForeignColumns($columns, $rows);
    }

    /**
     * Sets the properties of the specified column.
     *
     * @param  array $columns
     * @param  array $rows
     * @return array
     */
    protected function setForeignColumns(array $columns, array $rows)
    {
        foreach ($columns as $index => $column) {
            if ($column->getField() == $rows[$index]->from) {
                $column->setForeign(true);

                $column->setReferencedField($rows[$index]->to);
                $column->setReferencedTable($rows[$index]->table);
            }
        }

        return $columns;
    }

    /**
     * Sets the properties of the specified column.
     *
     * @param  mixed                   $row
     * @param  \Rougin\Describe\Column &$column
     * @return void
     */
    protected function setProperties($row, Column &$column)
    {
        if (! $row->notnull) {
            $column->setNull(true);
        }

        if ($row->pk) {
            $column->setPrimary(true);
            $column->setAutoIncrement(true);
        }
    }
}
