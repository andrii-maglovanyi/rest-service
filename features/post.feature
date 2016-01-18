Feature: post
  In order to manage posts via REST service
  As a regular user
  I need to be able to call GET, POST, PUT and DELETE methods

Scenario: Add a new post
  Given I send a post message of "Test post" to the url "http://rest-service.loc/post"
  When I call "POST"
  Then it should return status code of "201" and the message "Submitted data is saved."

Scenario: Update a post
  Given I send a post message of "Another post" and id of "1" to the url "http://rest-service.loc/post"
  When I call "PUT"
  Then it should return status code of "200" and the message "Submitted data is updated."

Scenario: Get post by ID
  Given I request the url of "http://rest-service.loc/post/1"
  When I call "GET"
  Then it should return status code of "200" and the the post with id of "1" and message "Another post"

Scenario: List all posts
  Given I request the url of "http://rest-service.loc/post"
  When I call "GET"
  Then it should return status code of "200" and the count of entries not less than "1"

Scenario: Delete a post
  Given I request the url of "http://rest-service.loc/post/1"
  When I call "DELETE"
  Then it should return status code of "200" and the message "Entry is deleted."