<?php

namespace RestService\src\Validators;

use RestService\app\Components\Model\AbstractValidator;
use RestService\app\Components\Model\AbstractEntity;

class PostValidator extends AbstractValidator
{
    protected $entityClass = 'RestService\src\Entities\Post';

    protected function validationRules(AbstractEntity $entity)
    {
        foreach ($entity->getData() as $key => $value) {
            // Check if post is not longer than 20 characters
            if ($key == 'post' && strlen($value) > 140) {
                $this->addValidationError($key, "Should not exceed 20 characters");
            }
        }
    }
}
