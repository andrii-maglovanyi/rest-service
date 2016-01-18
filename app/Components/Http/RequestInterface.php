<?php
namespace RestService\app\Components\Http;

interface RequestInterface
{
    // Reuqest types
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    // Content types
    const APPLICATION_JSON_CONTENT_TYPE = 'application/json';
    const APPLICATION_X_WWW_FORM_URLENCODED = 'application/x-www-form-urlencoded';
    const MULTIPART_FORM_DATA = 'multipart/form-data';

    /**
     * Get requst method
     *
     * @return string
     */
    public function getRequestMethod();

    /**
    * Set supported request methods
    *
    * @param array $requestMethods
    *
    * @return void
    */
    public function setSupportedRequestMethods(array $requestMethods = array());

    /**
     * Ger supported request methods
     *
     * @return array
     */
    public function getSupportedRequestMethods();

    /**
     * Set supported content types
     *
     * @param array $contentTypes
     *
     * @return void
     */
    public function setSupportedContentTypes(array $contentTypes = array());

    /**
     * Check if request method is supported
     *
     * @return bool
     */
    public function isRequestMethodSupported();

    /**
     * Check if content type is supported
     *
     * @return bool
     */
    public function isContentTypeSupported();

    /**
    * Set parameters of the action
    *
    * @param array $params
    *
    * @return void
    */
    public function setParams(array $params = array());

    /**
     * Get specified parameter
     *
     * @param  string $name Name of a parameter
     *
     * @return mixed        Value of a parameter
     */
    public function getParam($name);

    /**
     * Get parameters
     *
     * @return array
     */
    public function getParams();
}
