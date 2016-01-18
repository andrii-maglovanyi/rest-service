<?php

namespace RestService\app\Components\Db;

use PDO;

class Adapter implements AdapterInterface
{
    /**
     * Configuration data
     *
     * @var array
     */
    protected $config = array();

    /**
     * Database resource
     *
     * @var PDO
     */
    protected $connection;

    /**
     * ID column name
     *
     * @var string
     */
    protected $idColumnName;

    /**
     * Table name
     *
     * @var string
     */
    protected $table;

    /**
     * Prepared query
     *
     * @var PDOStatement
     */
    protected $pdoStatement;

    public function __construct(PDO $pdo)
    {
        try {
            $this->connection = $pdo;
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \RunTimeException($e->getMessage());
        }
    }

    public function setIdColumnName($idColumnName)
    {
        $this->idColumnName = $idColumnName;

        return $this;
    }

    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    public function select(array $params = array())
    {
        // Create a basic select query
        $query = "select * from {$this->table}";

        // If ID is present in the params
        if (isset($params[$this->idColumnName])) {
            // Create a query with a where statement
            $query = "select * from {$this->table} where {$this->idColumnName}=:{$this->idColumnName}";
        }

        // Convert params for the query
        $this->convertParams($params);

        // Fetch data
        return $this->prepare($query)
            ->fetch($params);
    }

    public function insert(array $params = array())
    {
        // Create a strin of fields that should be updated
        $queryColumns = implode(',', array_keys($params));

        // Create a string of values that correspond their fields
        $queryValues = implode(',', array_map(
            function ($val) {
                return ':'.$val;
            },
            array_keys($params)
        ));

        // Convert params for the query
        $this->convertParams($params);

        // Execute an insert query
        return $this->prepare("
            insert into
                {$this->table}({$queryColumns})
            values
                ({$queryValues})
        ")
        ->execute($params);
    }

    public function update(array $params = array())
    {
        $queryColumnsValues = implode(',', array_map(
            function ($val) {
                return "{$val}=:{$val}";
            },
            array_keys($params)
        ));

        // Convert params for the query
        $this->convertParams($params);

        return $this->prepare("
            update
                {$this->table}
            set
                {$queryColumnsValues}
            where
                {$this->idColumnName}=:{$this->idColumnName}
        ")
        ->execute($params);
    }

    public function delete(array $params = array())
    {
        // Convert params for the query
        $this->convertParams($params);

        // Execute a delete query
        return $this->prepare("
            delete from
                {$this->table}
            where
                {$this->idColumnName}=:{$this->idColumnName}
        ")
        ->execute($params);
    }


    /**
     * Convert params to match query requirements
     *
     * @param  array  $params   Query parameters
     *
     * @return void
     */
    protected function convertParams(array &$params = array())
    {
        $convertedParams = array();

        // Loop through the data to create params
        foreach ($params as $key => $value) {
            $convertedParams[':'.$key] = $value;
        }

        $params = $convertedParams;
    }

    /**
     * Prepare statement
     *
     * @param  string $query Query to be prepared for execution
     *
     * @return Adapter
     * @throws RunTimeException
     */
    protected function prepare($query)
    {
        try {
            $this->pdoStatement = $this->connection->prepare($query);

            return $this;
        } catch (\PDOException $e) {
            throw new \RunTimeException($e->getMessage());
        }
    }

    /**
     * Execute a query and fetch data as an assoc array
     *
     * @param  array  $params   Query parameters
     *
     * @return array            Fetched data
     */
    protected function fetch(array $params = array())
    {
        // Execute the statement
        $this->pdoStatement->execute($params);

        // Fetch data
        return $this->pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Execute an insert, update and delete queries
     *
     * @param  array  $params   Query parameters
     * 
     * @return int              Rows affected
     * @throws RunTimeException
     */
    protected function execute(array $params = array())
    {
        try {
            // Execute the statement
            $this->pdoStatement->execute($params);

            // Return a number of rows affected
            return $this->pdoStatement->rowCount();
        } catch (\PDOException $e) {
            throw new \RunTimeException($e->getMessage());
        }
    }
}
