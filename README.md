##rest-service

### Description

The project is a simple, restful web application that supports standard CRUD operations on blog posts.
The communication is being held in JSON format.

#### Setup

* Make sure you have the PHP 5.6 and MySQL installed
* Clone the project to an executable directory of your php engine
* Create a table in your database from the dump.sql file that can be found in the project root folder
* Edit config file of the project according to your local settings. The config.yml file can be found in the config folder of the project
* If you use Google Chrome, make sure it has a rest client installed. There are extensions available as Postman for Google Chrome or RESTClient for Firefox

#### Usage
 
Open a rest client of your choice and be sure to select the application/json content type.

* To create the first record, submit the POST request http://localhost/posts and enter the raw JSON content of your choice based on the following pattern: {"post":"The Hello World post", "date":"2016-05-31 12:00:00"}. Note that all fields are mandatory. The request should return: 201 Created on success, 400 Bad Request or 500 Internal Server Error.
* To retrieve the records, submit the GET request http://localhost/posts/{ID}. The request should return 200 OK or 404 Not Found.
* To update the records, submit the PUT request http://localhost/posts/{ID} with the raw JSON content, like: {"post":"Updated Hello World post", "date":"2016-05-31 12:10:00"}. The request should return 200 OK, 404 Not Found, 400 Bad Request or 500 Internal Server Error. Note that mentioning all of the JSON entries is not important. The script will update those which are mentioned in the raw JSON content.
* To delete the records, submit the DELETE request http://localhost/posts/{ID}. The request should return 200 OK, 404 Not Found, 500 Internal Server Error or 400 Bad Request.

#### Used technologies
Application's been made using:

* PHP 5.6
* MySQL
* Composer
* Pimple
* YAML
* PHPUnit
* Behat

#### Application directory Layout

```
app/
  Components/
    Controllers/ --> front controller
    Db/          --> database adapter
    Http/        --> request and response objects
    Model/       --> abstract entity, mapper and validator
config/          --> application configuration, DiC configuration
features/        --> BDD tests
src/
  Controllers/   --> application layer
  Entities/      --> anemic domain models
  Mappers/       --> database mappers
  Validators/    --> entity data validators
tests/           --> Unit tests
web/
  index.php --> the main php
```

##### Who do I talk to?

Should you have any questions or seek further clarifications, please feel free to contact me at andrii.maglovanyi@gmail.com
