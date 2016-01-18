<?php

namespace RestService\app\Components\Model;

use RestService\app\Components\Model\AbstractEntity;

abstract class AbstractValidator
{
    /**
     * Validation errors stack
     *
     * @var array
     */
    protected $validationErrors = array();

    /**
     * Run entity values through validation rules
     *
     * @param  AbstractEntity $entity Entity to be validated
     *
     * @return bool                   Validation has passed or not
     */
    public function validate(AbstractEntity $entity)
    {
        // Collect errors by looping through the validation resourcebundle_locales
        $this->validationRules($entity);

        // If no validation errors present, the validation passes successfully
        return (bool)count($this->validationErrors) == 0;
    }

    /**
     * Get validation errors
     *
     * @return array Validation erros
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    /**
     * Add validation error
     *
     * @param string $property Name of a property being validated
     * @param string $error    Error message
     *
     * @return void
     */
    protected function addValidationError($property, $error)
    {
        $this->validationErrors[$property][] = $error;
    }
}
