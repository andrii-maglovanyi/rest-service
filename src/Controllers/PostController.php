<?php
namespace RestService\src\Controllers;


use RestService\app\Components\Controllers\AbstractController;
use RestService\app\Components\Http\Request;
use RestService\app\Components\Http\Response;
use RestService\src\Mappers\PostMapper;
use RestService\src\Validators\PostValidator;
use RestService\src\Entities\Post;

class PostController extends AbstractController
{
    /**
     * Response object
     * @var Response
     */
    private $response;

    /**
     * Request object
     * @var Request
     */
    private $request;

    public function indexAction(Request $request)
    {
        $this->request = $request;
        $this->response = $this->container['response'];

        // Set supported request methods
        $this->request->setSupportedRequestMethods(
            array(Request::GET, Request::POST, Request::PUT, Request::DELETE)
        );

        // Set supported content types
        $this->request->setSupportedContentTypes(
            array(Request::APPLICATION_JSON_CONTENT_TYPE)
        );

        // Check if passed requested method is supported
        if (!$this->request->isRequestMethodSupported()) {
            echo $this->response->sendJson(Response::METHOD_NOT_ALLOWED_CODE);
            die;
        }

        switch ($this->request->getRequestMethod()) {
            case Request::GET:
                echo $this->getPosts($request->getParam('id'));
                break;
            case Request::POST:
                echo $this->createPost();
                break;
            case Request::PUT:
                echo $this->updatePost($request->getParam('id'));
                break;
            case Request::DELETE:
                echo $this->deletePost($request->getParam('id'));
                break;
        }
    }

    private function getPosts($id)
    {
        $mapper = new PostMapper($this->container['adapter']);
        // If ID is present, get posts by ID
        if ($id) {
            $post = $mapper->findById($id);
            // If post doesn't exist, throw not found
            if (empty($post)) {
                return $this->response->sendJson(Response::NOT_FOUND_CODE, "No post with such ID is found.");
            }

            $data = $post->getData();
        } else {
            // Get all posts
            $data = $mapper->findAll();
            // If posts not exist, throw not found
            if (empty($data)) {
                return $this->response->sendJson(Response::NOT_FOUND_CODE, "No posts found.");
            }
        }

        // Return posts
        return $this->response->sendJson(Response::OK_CODE, $data);
    }

    private function createPost()
    {
        if (!$this->request->isContentTypeSupported()) {
            echo $this->response->sendJson(Response::METHOD_NOT_ACCEPTABLE_CODE);
            die;
        }

        $post = new Post();

        // Set json data
        $post->setData($this->request->getParams());

        $validator = new PostValidator();
        // Check if validation succeeds
        if ($validator->validate($post)) {
            $mapper = new PostMapper($this->container['adapter']);
            if ($mapper->save($post)) {
                // Return posts
                return $this->response->sendJson(Response::CREATED_CODE, "Submitted data is saved.");
            }

            // Could not persis data
            return $this->response->sendJson(Response::INTERNAL_SERVER_ERROR_CODE);
        }

        // Validation fails
        return $this->response->sendJson(
            Response::BAD_REQUEST_CODE,
            'Submitted data is invalid',
            $validator->getValidationErrors()
        );
    }

    private function updatePost($id)
    {
        // Abort request if ID is not present or content type is not supported
        if (empty($id) || !$this->request->isContentTypeSupported()) {
            return $this->response->sendJson(Response::BAD_REQUEST_CODE);
        }

        $validator = new PostValidator();
        // Check if validation succeeds

        // Retrieved post by ID
        $mapper = new PostMapper($this->container['adapter']);
        $post = $mapper->findById($id);

        // If post does not exist, throw not found
        if (empty($post)) {
            return $this->response->sendJson(Response::NOT_FOUND_CODE, "No post with such ID is found.");
        }

        // Get json data
        $data = $this->request->getParams();

        // Check if data is invalid
        if (empty($data)) {
            return $this->response->sendJson(Response::BAD_REQUEST_CODE);
        }

        // Set data to post entity
        $post->setData($data);

        if ($validator->validate($post)) {
            // Check if data persists
            if ($mapper->save($post)) {
                return $this->response->sendJson(Response::OK_CODE, "Submitted data is updated.");
            }

            // If could not persist data
            return $this->response->sendJson(Response::INTERNAL_SERVER_ERROR_CODE);
        }

        // Validation fails
        return $this->response->sendJson(Response::BAD_REQUEST_CODE, "Submitted data is invalid.",
            $validator->getValidationErrors());
    }

    private function deletePost($id)
    {
        // Abort request if ID is not present
        if (empty($id)) {
            return $this->response->sendJson(Response::BAD_REQUEST_CODE);
        }

        // Retrieved post by ID
        $mapper = new PostMapper($this->container['adapter']);
        $post = $mapper->findById($id);

        // If post does not exist, throw not found
        if (empty($post)) {
            return $this->response->sendJson(Response::NOT_FOUND_CODE, "No post with such ID is found.");
        }

        // Attempt to delete a post
        if ($mapper->delete($id)) {
            return $this->response->sendJson(Response::OK_CODE, "Entry is deleted.");
        }

        // Deletion fails
        return $this->response->sendJson(Response::INTERNAL_SERVER_ERROR_CODE);
    }
}
