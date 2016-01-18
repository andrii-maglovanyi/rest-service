<?php

namespace RestService\tests;

use Pimple\Container;
use RestService\app\Config;

abstract class BaseDbTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    protected $container;

    // Only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // Only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;

    /**
     * BaseDbTestCase constructor. Configure Pimple container.
     *
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function  __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->container = new Container();
        Config::init($this->container);

        $this->container['database.host'] = $GLOBALS['DB_HOST'];
        $this->container['database.username'] = $GLOBALS['DB_USER'];
        $this->container['database.password'] = $GLOBALS['DB_PASSWD'];
        $this->container['database.db'] = $GLOBALS['DB_DBNAME'];
        $this->container['database.dsn'] = $GLOBALS['DB_DSN'];
    }

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = $this->container['pdo'];
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $this->container['database.db']);
        }

        return $this->conn;
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(
            __DIR__."/data/posts.yml"
        );
    }
}
