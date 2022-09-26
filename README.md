# Popularity of terms

## Introduction
The application calculates the popularity of terms.

## Installation

1. Download the repository zip then extract the zip.
2. Make a copy of the `.env.dist` file `cp .env.dist .env`. Add needed credentials
3. Build and run the application:
 - `docker-compose build`
 - `docker-compose up -d`
4. Run the command `bash docker exec -it php bash`.
5. Change the directory `cd term-popularity`.
6. Set up the dependencies `composer install`.
7. Make migrations `php bin/console doctrine:migrations:migrate`.
8. Add a new host `term-popularity.loc` to the `/etc/hosts` file 

The application can be accessed via `http://term-popularity.loc/`

## Running automated tests
1. docker exec -it php bash
2. php bin/phpunit

## Testing

1. Run an API platform, e.g. Postman
2. The endpoint `http://term-popularity.loc/score/github/{word}` is available now

## API description

A description of the Api can be found here `docs/term-popularity.oas3.yml`