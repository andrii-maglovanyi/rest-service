<?php
namespace RestService\app\Components\Controllers;

use Pimple\Container;

interface FrontControllerInterface
{
    // Default controller suffix
    const CONTROLLER_SUFFIX = "Controller";
    // Default action suffix
    const ACTION_SUFFIX = "Action";

    // Default controller name
    const DEFAULT_CONTROLLER = "Index";
    // Default action name
    const DEFAULT_ACTION = "index";

    /**
     * Run the appropriate action in a controller along with the specified parameters
     *
     * @param  Container $container Pimple DI Container
     * 
     * @return void
     */
    public function run(Container $container);

    /**
     * Get controller name
     *
     * @return string
     */
    public function getController();

    /**
     * Get action name
     *
     * @return string
     */
    public function getAction();
}
