# Laravel 8 Application - README

This repository contains a Laravel 8 application built using PHP 7.3. The application focuses on managing movies, directors, actors, and TV shows through a RESTful API. Follow the instructions below to set up and run the application.

## Prerequisites

- PHP 7.3
- Composer
- Database MySQL

## Getting Started

1. Clone this repository to your local machine:

```bash
git clone <repository_url>
```

2. Create a `.env` file in the root directory of the project. You can use the provided `.env.example` file as a template.

3. Configure the database connection in the `.env` file by setting the appropriate database driver, host, port, database name, username, and password.

4. Open a terminal and navigate to the project directory.

5. Install the project dependencies using Composer:

```bash
composer install
```

6. Generate a new application key:

```bash
php artisan key:generate
```

7. Generate the JWT secret key:

```bash
php artisan jwt:secret
```

8. Create the desired database for the application in your chosen database management system.

9. Run database migrations to create the necessary tables:

```bash
php artisan migrate
```

## API Endpoints

### User

- Register User:
  - Endpoint: `../public/api/register`
  - Method: POST

- Login:
  - Endpoint: `../public/api/login`
  - Method: POST

- Logout:
  - Endpoint: `../public/api/logout`
  - Method: POST

- User:
  - Endpoint: `../public/api/user`
  - Method: GET

### Movies

- Create a movie (Requires a created director):
  - Endpoint: `../public/api/moviecreate`
  - Method: POST

- Get movies (Filter by id, genre, title, or retrieve all):
  - Endpoint: `../public/api/movie`
  - Method: GET

### Actors

- Create an actor:
  - Endpoint: `../public/api/actorcreate`
  - Method: POST

- Get actors (Filter by name or actor_id):
  - Endpoint: `../public/api/actor`
  - Method: GET

### Directors

- Create a director:
  - Endpoint: `../public/api/directorcreate`
  - Method: POST

- Get directors (Filter by name or director_id):
  - Endpoint: `../public/api/director`
  - Method: GET

### TV Shows

- Create a TV show:
  - Endpoint: `../public/api/tvshowcreate`
  - Method: POST

- Get TV shows (Filter by id, genre, title, or retrieve all):
  - Endpoint: `../public/api/tvshow`
  - Method: GET

- Create episodes for a TV show:
  - Endpoint: `../public/api/addepisodes`
  - Method: POST

- Get episodes by TV show id:
  - Endpoint: `../public/api/episode`
  - Method: GET

### Adding Actors

- Add actors to a movie:
  - Endpoint: `../public/api/addactormovie`
  - Method: POST

- Add actors to a TV show:
  - Endpoint: `../public/api/addactortv`
  - Method: POST
