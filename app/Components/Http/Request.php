<?php

namespace RestService\app\Components\Http;

class Request implements RequestInterface
{
    /**
     * Request method
     *
     * @var string
     */
    protected $requestMethod;

    /**
     * Request content type
     *
     * @var array
     */
    protected $requestContentTypes = array();

    /**
     * Array of supported request methods
     *
     * @var array
     */
    protected $supportedRequestMethods = array();

    /**
     * Array of supported content types
     *
     * @var array
     */
    protected $supportedContentTypes = array();

    /**
     * Request parameters
     *
     * @var array
     */
    protected $params = array();

    public function __construct()
    {
        $this->requestMethod = isset($_SERVER['REQUEST_METHOD']) ?
            $_SERVER['REQUEST_METHOD'] : 'GET';
        $this->requestContentTypes = isset($_SERVER['CONTENT_TYPE']) ?
            array_map('trim', explode(',', $_SERVER['CONTENT_TYPE'])) : array();
    }

    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    public function setSupportedRequestMethods(array $requestMethods = array())
    {
        $this->supportedRequestMethods = $requestMethods;
    }

    public function getSupportedRequestMethods()
    {
        return $this->supportedRequestMethods;
    }

    public function setSupportedContentTypes(array $contentTypes = array())
    {
        $this->supportedContentTypes = $contentTypes;
    }

    public function isRequestMethodSupported()
    {
        return in_array($this->requestMethod, $this->supportedRequestMethods);
    }

    public function isContentTypeSupported()
    {
        foreach ($this->requestContentTypes as $contentType) {
            foreach ($this->supportedContentTypes as $supportedContentType) {
                if ($contentType == $supportedContentType) {
                    return true;
                }
            }
        }

        return false;
    }

    public function setParams(array $params = array())
    {
        $this->params = $params;
    }

    public function getParam($name)
    {
        $params = $this->getParams();

        // If there is a single param with index of zero - this must be ID
        if (count($params) == 1 && isset($params[0])) {
            $params['id'] = $params[0];
        }
        return isset($params[$name]) ? $params[$name] : null;
    }

    public function getParams()
    {
        foreach ($this->requestContentTypes as $contentType) {
            if ($contentType != self::MULTIPART_FORM_DATA) {
                // Get json data and return it as an assoc array
                $input = json_decode(file_get_contents('php://input'), true);
                if (is_array($input)) {
                    $this->params = array_merge($this->params, $input);
                }
            }
        }

        return $this->params;
    }
}
