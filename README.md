# Tic-Tac-Toe
A simple API to play Tic-Tac-Toe.

## Install 
The API is written in PHP and uses [Symfony framework](https://symfony.com/) and PostgreSQL as datastore.  
- [Symfony requirements](https://symfony.com/)
- Postgresql 13
- Docker and docker-compose (The DB is running on a docker container)
Once requirements are met it is possible to configure the project as follows:
- Run in the root directory of the project `composer install`
 - Setup your env variables inside .env files

## Usage
There are three script inside the project:
- *start_api.sh* starts the API and docker database
 - *start_api.sh* stops] the API and docker database
 - *start_api_test.sh* runs a series of played out game to show API usage
Documentation about API usage are provided by swagger reachable connecting to `http://127.0.0.1:800` from a web browser.

## Improvements
This was written in a few hours to better understand Symfony and PHP language so there is a lot of room for improvements.
For starter this is using Symfony webserver, the best solution here would be to run a Nginx dockerized instance instead.
Also, the whole project iteself should be also dockerized in order to have a cleaner deployment in production.
As final thoughts it would make sense to abstract the game logic to support [Super Tic-Tac-Toe](https://en.wikipedia.org/wiki/Ultimate_tic-tac-toe) and other sizes of tris (4x4, 5x5, NxN...)