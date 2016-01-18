<?php

namespace RestService\tests\app\Db;

use RestService\tests\BaseDbTestCase;
use RestService\app\Components\Db\Adapter;

class AdapterTest extends BaseDbTestCase
{
    private $adapter;

    public function setUp()
    {
        parent::setUp();
        $this->adapter = new Adapter($this->container['pdo']);
        $this->adapter->setTable('posts');
    }

    public function testAdapterSelectsAllData()
    {
        $this->assertEquals(2, $this->getConnection()->getRowCount('posts'), "Pre-Condition");
        $result = $this->adapter->select();
        $this->assertEquals(2, count($result), "Selecting all entries failed");
    }

    public function testAdapterSelectsDataById()
    {
        $this->adapter->setIdColumnName('id');
        $result = $this->adapter->select(array('id' => 2));
        $this->assertEquals('I like it!', $result[0]['post'], "Selecting entry by ID failed");
    }

    public function testAdapterInsertsData()
    {
        $this->adapter->insert(array('post' => 'Test insert entry', 'date' => date('Y-m-d H:i:s')));
        $this->assertEquals(3, $this->getConnection()->getRowCount('posts'), "Inserting failed");
    }

    public function testAdapterUpdatesData()
    {
        $this->adapter->setIdColumnName('id');
        $this->adapter->update(array('id' => 2, 'post' => 'Test update entry', 'date' => date('Y-m-d H:i:s')));
        $result = $this->adapter->select(array('id' => 2));
        $this->assertEquals('Test update entry', $result[0]['post'], "Updating entry failed");
    }

    public function testAdapterDeletesData()
    {
        $this->adapter->setIdColumnName('id');
        $this->adapter->delete(array('id' => 1));
        $result = $this->adapter->select();
        $this->assertEquals(1, count($result), "Deleting entry failed");
    }

    public function testAdapterThrowsExceptionTableDoesntExist()
    {
        $this->setExpectedException('RunTimeException');
        $this->adapter->setTable('no_table');
        $this->adapter->select();
    }

    public function testAdapterThrowsExceptionIdWrongData()
    {
        $this->setExpectedException('RunTimeException');
        $this->adapter->insert(array('wrong_field' => 'No value'));
    }
}
