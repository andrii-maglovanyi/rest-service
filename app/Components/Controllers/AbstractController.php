<?php

namespace RestService\app\Components\Controllers;

use Pimple\Container;

abstract class AbstractController
{
    /**
     * Pimple container to be used as service locator
     *
     * @var Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
