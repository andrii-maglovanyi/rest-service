<?php
namespace RestService\src\Controllers;

class IndexController
{
    /**
     * The default action of the controller
     */
    public function indexAction()
    {
        echo "Hello World!";
    }

    public function aboutAction()
    {
        echo "This is the about page";
    }
}