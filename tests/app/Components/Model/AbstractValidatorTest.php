<?php

namespace RestService\tests\app\Components\Model;

use RestService\tests\BaseTestCase;
use RestService\src\Entities\Post;
use RestService\src\Validators\PostValidator;

class AbstractValidatorTest extends BaseTestCase
{
    protected $data = array();
    protected $post;

    public function setUp()
    {
        $this->data = [
            'id' => 1,
            'post' => 'This is a very-very long post, with a lot of redundant information that exceeds the limit and
                causes the validation check to fail',
            'date' => date("Y-m-d H:i:s")
        ];

        $this->post = new Post();
        $this->validator = new PostValidator();
        $this->post->setData($this->data);
    }

    public function testValidationFails()
    {
        $this->assertFalse($this->validator->validate($this->post));
    }

    public function testValidatorReturnsErrorsArray()
    {
        $this->validator->validate($this->post);
        $this->assertCount(1, $this->validator->getValidationErrors());
        $this->assertArrayHasKey('post', $this->validator->getValidationErrors());
    }
}
