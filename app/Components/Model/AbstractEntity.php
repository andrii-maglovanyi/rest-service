<?php

namespace RestService\app\Components\Model;

abstract class AbstractEntity
{
    /**
     * Populate entity with array of data
     *
     * @param array $data
     *
     * @return void
     */
    public function setData(array $data = array())
    {
        foreach ($data as $key => $value) {
            // If setter for the property exists, use it
            $method = 'set'.ucwords($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
                continue;
            }

            // If data values is appropriate, add it to the data array
            if (property_exists($this, $key)) {
                $this->{'set'.ucwords($key)}($value);
            }
        }
    }

    /**
     * Return entity values as array, or single value if parameter provided
     *
     * @param  string $param Name of entity property
     *
     * @return mixed         Propery value(s)
     */
    public function getData($param = null)
    {
        $props = get_object_vars($this);
        // Recursive collecting entities data into array
        foreach ($props as &$property) {
            if (is_object($property) && method_exists($property, 'getData')) {
                $property = $property->getData();
            }
        }

        // Get single value if parameter provided
        if ($param) {
            return $props[$param];
        }

        return $props;
    }
}
