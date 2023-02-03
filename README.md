# EGIN_backend - Starter Kit - Symfony 5.4

## Getting Started

### Prerequisites

1. Check composer is installed
2. Check symofony CLI is installed

### Install

1. Clone this project
2. Run `composer install`

### Working

1. Run `symfony console d:d:c` to create database, `symfony console make:migration` and `symfony console d:m:m` to create and export a migration, `symfony console d:f:l` to load fixtures
2. Run `symfony server:start` to launch your local php web server
3. Run `yarn run dev --watch` to launch your local server for assets

### Testing

1. Run `symfony console d:d:c` to create database for test, and `symfony console d:f:l` to load fixtures into it
2. Run `php bin/phpunit` to run your application tests

### Stripe

1. Run `stripe listen --forward-to localhost:8000/api/webhooks --skip-verify` for start server test stripe to connect application to webhook's stripe
