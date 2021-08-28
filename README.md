# Tekfeed Middle API

## Aim
Reducing googleapis CustomSearch cost by storing the result and returning it when asked again.

## Prerequisite

PHP >= 7.4

[Composer](https://getcomposer.org/)

A web server

## Usage

You will have to create .env file.

To do so, copy *.env.sample* and provide all the information asked in the file.

Execute the command : 

    $ composer install

Before being able to store any call you will have to create a database named *tekfeed_storage*

To init the tables you can just access the page : /seed of the api, it will create the table.

## Changing Database Name

You can change the Database Name directly in 

***bootstrap.php***
