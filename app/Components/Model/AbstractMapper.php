<?php

namespace RestService\app\Components\Model;

use \RestService\app\Components\Db\AdapterInterface;

abstract class AbstractMapper
{
    /**
     * Entity ID column name
     *
     * @var string
     */
    protected $entityIdColumnName;

    /**
     * Entity table name, if not defined - table is guessed by name of entity
     *
     * @var string
     */
    protected $tableName;

    /**
     * Class name of entity, used to create an entity when searched by ID
     *
     * @var string
     */
    protected $entityClass;

    /**
     * Data Access Layer (DAL)
     *
     * @var AdapterInterface
     */
    protected $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->adapter->setIdColumnName($this->entityIdColumnName);
        $this->setTableName();
    }

    /**
     * Find all records
     *
     * @return array
     */
    public function findAll()
    {
        return $this->adapter->select();
    }

    /**
     * Find record by ID
     *
     * @param  mixed $id    ID of a record
     *
     * @return Entity|null
     */
    public function findById($id)
    {
        $data = $this->adapter->select(array($this->entityIdColumnName => $id));

        if (isset($data[0])) {
            // Create new entity and populate data
            $entity = new $this->entityClass();
            $entity->setData($data[0]);

            // Return entity object
            return $entity;
        }

        return null;
    }

    /**
     * Persist data to the Database
     *
     * @param  Entity $entity   Entity to be saved
     *
     * @return int              A number of affected rows
     */
    public function save(AbstractEntity $entity)
    {
        // Execute an update if ID is present in data
        if ($entity->getData($this->entityIdColumnName) != null) {
            // If ID is present, run an update statement and check if one record is updated
            return $this->adapter->update($entity->getData());
        }
        // Otherwise, run an insert statement and check if one record is added
        return $this->adapter->insert($entity->getData());
    }

    /**
     * Delete post by ID
     *
     * @param  mixed $id    ID of a record
     *
     * @return int          A number of affected rows
     */
    public function delete($id)
    {
        return $this->adapter->delete(array($this->entityIdColumnName => $id));
    }

    /**
     * Set table name to the DAL adapter, if table name is not set - try to
     * guess it by entity name
     *
     * @return void
     */
    private function setTableName()
    {
        // Get table name from name of entity if not defined
        if (!$this->tableName) {
            $path = explode('\\', get_class($entity));
            $this->tableName = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', array_pop($path))).'s';
        }

        // Set table to be used
        $this->adapter->setTable($this->tableName);
    }
}
