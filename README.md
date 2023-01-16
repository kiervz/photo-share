# ![PhotoShare API](logo.png)

This is the Backend API of a fullstack Photo Sharing web app built with React, Tailwind CSS, Laravel and MySQL.

Check the demo deployed at AWS EC2 (free tier): http://54.179.174.127

The Frontend of this API can be found [here](https://github.com/kiervz/photo-sharer-fe).

----------

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/8.x/installation)

Clone the repository

    git clone https://github.com/kiervz/photo-share.git

Switch to the repo folder

    cd photo-share

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

In the .env file, add database information to allow Laravel to connect to the database and also the AWS credentials to allow Laravel to save uploaded images to the AWS S3.

    SANCTUM_STATEFUL_DOMAINS=
    SESSION_DOMAIN=

    DB_CONNECTION=mysql
    DB_HOST=
    DB_PORT=
    DB_DATABASE=
    DB_USERNAME=
    DB_PASSWORD=

    AWS_ACCESS_KEY_ID=
    AWS_SECRET_ACCESS_KEY=
    AWS_DEFAULT_REGION=
    AWS_BUCKET=
    AWS_ENDPOINT_IMG="https://sample-url.s3.ap-southeast-1.amazonaws.com/photos/"

Generate a new application key

    php artisan key:generate

Run the **php artisan test** to check if there are any broken functionality of the program.

    php artisan test

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://127.0.0.1:8000
