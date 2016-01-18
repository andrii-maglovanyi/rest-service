<?php

namespace RestService\tests\app\Components\Model;

use RestService\tests\BaseDbTestCase;
use RestService\src\Entities\Post;
use RestService\src\Mappers\PostMapper;

class AbstractMapperTest extends BaseDbTestCase
{
    protected $data = array();
    protected $post;

    public function setUp()
    {
        $this->post = new Post();
        $this->mapper = new PostMapper($this->container['adapter']);
        $this->post->setData($this->data);
    }

    public function testMapperFetchAllData()
    {
        $this->assertCount(2, $this->mapper->findAll());
    }

    public function testMapperFindsItemById()
    {
        $post = $this->mapper->findById(2);
        $this->assertInstanceOf('RestService\src\Entities\Post', $post);
        $this->assertEquals($post->getPost(), 'I like it!');
    }

    public function testMappeReturnsNullIfNoItemFound()
    {
        $this->assertEmpty($this->mapper->findById(3));
    }

    public function testMapperSavesEntityCorrectly()
    {
        $this->assertEmpty($this->mapper->findById(3));
        $this->post->setData([
            'post' => 'This is a testing post',
            'date' => date("Y-m-d H:i:s")
        ]);

        $this->assertTrue((bool)$this->mapper->save($this->post));
        $this->assertTrue($this->post->getPost() === $this->mapper->findById(3)->getPost());
    }

    public function testMapperUpdatesEntityCorrectly()
    {
        $post = $this->mapper->findById(2);
        $this->assertEquals($post->getPost(), 'I like it!');

        $post->setPost('I don`t like it!');
        $this->assertTrue((bool)$this->mapper->save($post));
        $this->assertTrue($post->getPost() === $this->mapper->findById(2)->getPost());
    }

    public function testMapperDeletesEntityCorrectly()
    {
        $post = $this->mapper->findById(1);
        $this->assertEquals($post->getPost(), 'Hello buddy!');

        $this->assertTrue((bool)$this->mapper->delete($post->getId()));
        $this->assertEmpty($this->mapper->findById($post->getId()));
    }
}
