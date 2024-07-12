# Project Title

PHP - Take Home Assessment for PHP Symfony

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Architecture Overview](#architecture-overview)
- [API Documentation](#api-documentation)
- [Assumptions](#assumptions)


## Installation

Steps to install the project:

1. Clone the repository:

Copy the content of .env.example into .env:
```bash
cp .env.example .env
```

After Cloning this repo Navigate to the project directory and Run:
```bash
docker-compose up
```


## Usage
Run migration
```bash
docker exec -it symfony-products-test-products-1 php bin/console doctrine:migrations:migrate 
```
Run this command to seed user to the database
```bash
docker exec -it symfony-products-test-products-1 php bin/console doctrine:fixtures:load
```


You can access the application at ```http://localhost:8000```.

## Running Tests
Inside the user docker container run 
```./vendor/bin/phpunit```

## Running Static code analysis
Inside the user docker container run 
```bash
 docker exec -it symfony-products-test-products-1 vendor/bin/phpuni
```

    Run PHPStan:
```bash
    docker exec -it symfony-products-test-products-1   vendor/bin/phpstan analyse
```
    Run PHP_CodeSniffer:
```bash
    docker exec -it symfony-products-test-products-1   vendor/bin/phpc
```

## Api DOcumentation

- **API Endpoints**:
    - `POST /api/login` - Get a bearer token.

    - `GET /api/products` - List all products.
    - `POST /api/products` - Create a new product.
    - `GET /api/products/{id}` - Get details of a single product.
    - `PUT /api/products/{id}` - Update an existing product.
    - `DELETE /api/products/{id}` - Delete a product.

    All endpoints needs to be based a bearer token
    header: {

        Authorization: Bearer ey........
    } 


    POST /api/login

        Description: Authenticate users and retrieve a bearer token.
        Request Body:
        json
        {
            "username": "user@example.com",
            "password": "password"
        }

        Response:
        json
        {
            "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
        }

    Products
    GET /api/products

    Description: Retrieve a list of all products.
    Response:
    json
    {
    "status": "success",
    "data": [
        {
        "id": 1,
        "name": "Sample Product 1",
        "description": "Description for Sample Product 1",
        "price": "19.99"
        },
        {
        "id": 2,
        "name": "Sample Product 2",
        "description": "Description for Sample Product 2",
        "price": "29.99"
        }
    ]
    }
    POST /api/products

    Description: Create a new product.
    Request Body:
    json
    {
    "name": "New Product",
    "description": "Description of new product",
    "price": "15.99"
    }
    Response:
    json
    {
    "status": "success",
    "message": "Product created successfully",
    "data": {
        "id": 3,
        "name": "New Product",
        "description": "Description of new product",
        "price": "15.99"
    }
    }
    GET /api/products/{id}

    Description: Retrieve details of a single product.
    Response:
    json
    Copy code
    {
    "status": "success",
    "data": {
        "id": 1,
        "name": "Sample Product 1",
        "description": "Description for Sample Product 1",
        "price": "19.99"
    }
    }
    PUT /api/products/{id}

    Description: Update an existing product.
    Request Body:
    json
    {
    "name": "Updated Product Name",
    "description": "Updated description",
    "price": "20.99"
    }
    Response:
    json
    {
    "status": "success",
    "message": "Product updated successfully",
    "data": {
        "id": 1,
        "name": "Updated Product Name",
        "description": "Updated description",
        "price": "20.99"
    }
    }
    DELETE /api/products/{id}

    Description: Delete a product.
    Response:
    json
        {
        "status": "success",
        "message": "Product deleted"
        }



## Architecture oVerview
    This project is built using the Symfony framework. The architecture is built around the Model-View-Controller (MVC) pattern, ensuring a clean separation of concerns. The application provides a RESTful API for managing products and user.

    Components
    Web Server:

    Nginx: Handles HTTP requests and serves as the entry point for the application. It is configured to handle requests and serve static files efficiently. it listens on port 8000

    Application Framework:

    Symfony: Utilized for routing, controllers, and the overall management of business logic. Symfony's flexibility allows us to integrate various components such as Doctrine for ORM, and PHPUnit for testing.

    Database:
    MySQL: Used to persistently store data. Managed via Doctrine ORM within Symfony, allowing for database interactions to be abstracted through entities.

    Docker:

    Docker containers are used to encapsulate different parts of the application, ensuring consistent environments across development, testing, and production. This includes containers for the web server, PHP, and MySQL.

    Security:

    Implemented using Symfony’s security component, which handles authentication, authorization, and secure storage of user credentials.

    API:

    The application exposes RESTful endpoints for managing products and users. These endpoints accept and return JSON formatted data and follow standard HTTP verbs (GET, POST, PUT, DELETE).
    Data Flow

    Request Handling:

    All requests are received by the web server and forwarded to Symfony’s front controller (public/index.php).
    Requests are then routed to the appropriate controller based on the defined routes.


    Doctrine ORM abstracts the database interactions, allowing entities to be handled in an object-oriented manner.
    Transactions are used to ensure data integrity and handle complex operations involving multiple steps.
    Response:

    Responses are generated by controllers, typically as JSON for API endpoints.
    Error handling is built into the controllers and service layers to manage exceptions and return appropriate error messages and HTTP status codes.

    Testing:
    PHPUnit: Used for unit and functional testing to ensure the application behaves as expected under various scenarios.
    Static Analysis Tools: PHPStan and PHP_CodeSniffer ensure code quality and adherence to coding standards.


## Assumptions
    The application assumes a Dockerized environment for development and production, ensuring no discrepancies due to different configurations.
    A basic understanding of REST principles is required for interacting with the API.
