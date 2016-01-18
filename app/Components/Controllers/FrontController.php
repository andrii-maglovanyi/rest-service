<?php
namespace RestService\app\Components\Controllers;

use Pimple\Container;
use RestService\app\Components\Http\Request;
use InvalidArgumentException;

class FrontController implements FrontControllerInterface
{
    /**
     * Controller name
     *
     * @var string
     */
    protected $controller;

    /**
     * Action name
     * @var string
     */
    protected $action;

    /**
     * The url array to store controller, action and their parameters
     *
     * @var array
     */
    protected $url;

    /**
     * Request object where all the parameters are set
     *
     * @var RequestInterface
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->setController(self::DEFAULT_CONTROLLER);
        $this->setAction(self::DEFAULT_ACTION);

        $this->request = $request;

        $this->parseUri();
    }

    public function run(Container $container)
    {
        $controller = new $this->controller($container);

        call_user_func_array(array(
            $controller,
            $this->action
        ), array($this->request));
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    /**
     * Parse URI to determine controller, action and params part of the URI
     *
     * @return void
     */
    protected function parseUri()
    {
        if (empty($_SERVER["REQUEST_URI"])) {
            return false;
        }

        // Get request URL without the forward slash "/"
        $path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");

        // The URL consists of controller, action and an array of parameters
        @list($this->url['controller'], $this->url['action'], $this->url['params']) = explode("/", $path, 3);

        // Check if controller is present
        if (!empty($this->url['controller'])) {
            $this->setController($this->url['controller']);
        }

        // Check if action is present
        if (!empty($this->url['action'])) {
            $this->setAction($this->url['action']);
        }

        // Check if parameters are present
        if (!empty($this->url['params'])) {
            $this->request->setParams(explode("/", $this->url['params']));
        }
    }

    /**
     * Set controller
     *
     * @param string $_controller   Name of a controller
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function setController($_controller)
    {
        // Compose a controller name to match the required syntax
        $controller = '\\RestService\\src\\Controllers\\'.ucfirst(strtolower($_controller)).self::CONTROLLER_SUFFIX;

        // If a controller class is not found, throw an exception
        if (!class_exists($controller)) {
            throw new InvalidArgumentException("The controller ($_controller) has not been defined.");
        }

        // Set controller to the class
        $this->controller = $controller;

        return $this;
    }

    /**
     * Set action of the controller
     *
     * @param string $action   Name of an action
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function setAction($action)
    {
        // Compose an action name to match the required syntax
        $method = strtolower($action).self::ACTION_SUFFIX;

        // If a custom method in the controller class does not exist
        if (!method_exists($this->controller, $method)) {
            // Set action part of the URL to be equaled to the default action
            $method = self::DEFAULT_ACTION.self::ACTION_SUFFIX;
            // Check if the default method exists to call the default action
            if (!method_exists($this->controller, $method)) {
                // Otherwise, throw an exception
                throw new InvalidArgumentException("The controller action ($action) has not been defined");
            }

            // Move parameter if exists, from the action part to the params of the URL
            $this->moveParamFromActionToParams($action);
        }

        // Set the method as an action of the class
        $this->action = $method;

        return $this;
    }

    /**
     * Move a parameter from the action part of the URL to the params
     *
     * @param  string $action Name of an action, which used as parameter value
     *
     * @return void
     */
    private function moveParamFromActionToParams($action)
    {
        // Check if params part is not empty
        if (!empty($this->url['params'])) {
            // Merge parameter taken from the action part with the rest of parameters that may appear in the params
            $this->url['params'] = $action.'/'.$this->url['params'];
        } else {
            // Otherwise, set a parameter taken from action to the params part
            $this->url['params'] = $action;
        }
    }
}
