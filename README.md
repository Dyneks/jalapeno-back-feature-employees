# Jalapeno-back

## Dependencies

* PHP
* MySQL
* [Composer](https://getcomposer.org)

## Setup

This app requires a database connection to be open with the credentials listed in `src/settings.php`.

Sample routes are listed in `src/routes.php`.

## Start

If this is your first launch, you'll need to pull down the dependencies. For that, just run `composer update`.

You can start the app with the following command:

`php -S localhost:8080 -t public public/index.php`

All endpoints will then be available via `localhost:8080/{endpoint}`.

CORS should be working as well.

## Database querying

This app currently does not support any ORM. All queries must be written, validated and executed manually.