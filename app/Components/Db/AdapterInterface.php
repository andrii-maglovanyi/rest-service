<?php

namespace RestService\app\Components\Db;

interface AdapterInterface
{
    /**
     * Set ID column name of a table
     *
     * @param string $idColumnName  ID column name
     *
     * @return AdapterInterface;
     */
    public function setIdColumnName($idColumnName);

    /**
     * Set table name
     *
     * @param string $table Table name
     *
     * @return AdapterInterface;
     */
    public function setTable($table);

    /**
     * Select a record
     *
     * @param  array  $params  Query parameters
     *
     * @return array           Retrieve data as an associative array
     * @throws RunTimeException
     */
    public function select(array $params = array());

    /**
     * Insert data
     *
     * @param  array  $params  Query parameters
     *
     * @return int             Number of rows affected
     * @throws RunTimeException
     */
    public function insert(array $params = array());


     /**
      * Update data
      *
      * @param  array  $params  Query parameters
      *
      * @return int             Number of rows affected
      * @throws RunTimeException
      */
    public function update(array $param = array());

    /**
     * Delete data
     *
     * @param  array  $param   Query parameters
     *
     * @return int             Number of rows affected
     * @throws RunTimeException
     */
    public function delete(array $param = array());
}
