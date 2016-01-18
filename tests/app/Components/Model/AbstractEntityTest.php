<?php

namespace RestService\tests\app\Components\Model;

use RestService\tests\BaseTestCase;
use RestService\src\Entities\Post;

class AbstractEntityTest extends BaseTestCase
{
    protected $data = array();
    protected $post;

    public function setUp()
    {
        $this->data = [
            'id' => 1,
            'post' => 'Test post',
            'date' => date("Y-m-d H:i:s")
        ];

        $this->post = new Post();
        $this->post->setData($this->data);
    }

    public function testEntitySetsDataArrayCorrectly()
    {
        $this->assertTrue($this->data === $this->post->getData());
    }

    public function testEntityGetsParameterCorrectly()
    {
        $this->assertTrue($this->data['date'] === $this->post->getData('date'));
    }

    public function testEntitySetsParameterCorrectly()
    {
        $this->post->setDate(date("d/m/Y"));
        $this->assertTrue(date("d/m/Y") === $this->post->getData('date'));
        $this->assertTrue($this->post->getDate() === $this->post->getData('date'));
    }
}
