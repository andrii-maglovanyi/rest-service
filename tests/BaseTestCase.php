<?php

namespace RestService\tests;

use Pimple\Container;
use RestService\app\Config;

abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected $container;

    /**
     * BaseTestCase constructor. Configure Pimple container.
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
    }
}
