<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use RestService\src\Validators\PostValidator;
use RestService\src\Entities\Post;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    private $ch;
    private $return;

    /**
     * @Given /^I request the url of "([^"]*)"$/
     */
    public function iRequestTheUrlOf($url)
    {
        $this->prepareRequest($url);
    }

    /**
     * @Given /^I send a post message of "([^"]*)" to the url "([^"]*)"$/
     */
    public function iSendAPostMessageOfToTheUrl($message, $url)
    {
        $this->prepareRequest($url);
        $payload = json_encode(array("post"=> $message, "date"=> date('Y-m-d H:i:s')));
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $payload);
    }

    /**
     * @Given /^I send a post message of "([^"]*)" and id of "([^"]*)" to the url "([^"]*)"$/
     */
    public function iSendAPostMessageOfAndIdOfToTheUrl($message, $id, $url)
    {
        $this->prepareRequest($url);
        $payload = json_encode(array("id" => $id, "post"=> $message, "date"=> date('Y-m-d H:i:s')));
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $payload);
    }


    /**
     * @When /^I call "([^"]*)"$/
     */
    public function iCall($method)
    {
        // Send request.
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $method);
        $this->return = curl_exec($this->ch);
        curl_close($this->ch);
    }

    /**
     * @Then /^it should return status code of "([^"]*)" and the message "([^"]*)"$/
     */
    public function itShouldReturnStatusCodeOfAndTheMessage($statusCode, $message)
    {
        $return = json_decode($this->return);
        if ((int)$return->code !== (int)$statusCode || $return->message !== $message) {
            throw new Exception ("Actural return is:\n" . $this->return);
        }
    }

    /**
     * @Then /^it should return status code of "([^"]*)" and the count of entries more than "([^"]*)"$/
     */
    public function itShouldReturnStatusCodeOfAndTheCountOfEntriesMoreThan($statusCode, $count)
    {
        $return = json_decode($this->return);
        if ((int)$return->code !== (int)$statusCode || count($return->message) < $count) {
            throw new Exception ("Actural return is:\n" . $this->return);
        }
    }

    /**
     * @Then /^it should return status code of "([^"]*)" and the count of entries not less than "([^"]*)"$/
     */
    public function itShouldReturnStatusCodeOfAndTheCountOfEntriesNotLessThan($statusCode, $message)
    {
        $return = json_decode($this->return);
        if ((int)$return->code !== (int)$statusCode || count($return->message) < $message) {
            throw new Exception ("Actural return is:\n" . $this->return);
        }
    }

    /**
     * @Then /^it should return status code of "([^"]*)" and the the post with id of "([^"]*)" and message "([^"]*)"$/
     */
    public function itShouldReturnStatusCodeOfAndTheThePostWithIdOfAndMessage($statusCode, $id, $message)
    {
        $return = json_decode($this->return);
        if ((int)$return->code !== (int)$statusCode || (int)$return->message->id !== (int)$id || $return->message->post !== $message) {
            throw new Exception ("Actural return is:\n" . $this->return);
        }
    }

    protected function prepareRequest($url)
    {
        $this->ch = curl_init($url);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        // Return response instead of printing.
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }
}
