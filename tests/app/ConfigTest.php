<?php

namespace RestService\tests\app;

use RestService\tests\BaseTestCase;
use RestService\app\Config;
use Pimple\Container;

class ConfigTest extends BaseTestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = new Container();
        Config::init($this->container);
    }

    /**
     * @param  [type] $name  [description]
     * @param  [type] $value [description]
     *
     * @dataProvider providerConfigNames
     */
    public function testContainerHasRegisteredConfigs($name, $value)
    {
        $this->assertEquals($value, $this->container[$name]);
    }

    /**
     * @param  [type] $class [description]
     * @param  [type] $name  [description]
     *
     * @dataProvider providerServicesNames
     */
    public function testContainerHasRegisteredServices($class, $name)
    {
        $this->assertInstanceOf($class, $this->container[$name]);
    }

    public function providerConfigNames()
    {
        return array(
            array('database.host', $GLOBALS['DB_HOST']),
            array('database.username', $GLOBALS['DB_USER']),
            array('database.password', $GLOBALS['DB_PASSWD']),
            array('database.db',  'rest_service'),
            array('database.dsn', "mysql:host={$GLOBALS['DB_HOST']};dbname=rest_service")
        );
    }

    public function providerServicesNames()
    {
        return array(
            array('\PDO', 'pdo'),
            array('RestService\app\Components\Db\Adapter', 'adapter'),
            array('RestService\app\Components\Http\Request', 'request'),
            array('RestService\app\Components\Message', 'message'),
            array('RestService\app\Components\Http\Response', 'response'),
            array('RestService\app\Components\Controllers\FrontController', 'front_controller')
        );
    }
}
