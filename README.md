# Tekfeed Middle API

## Aim
Reducing googleapis CustomSearch cost by storing the result and returning it when asked again.
Providing all the Data to the Mobile and Web app. Store all the data and regroup everything in one place to allow other students to use the collected data or to contribute to the data.

## Prerequisite

PHP >= 7.4

[Composer](https://getcomposer.org/)

The `chromium-driver` to fetch the rank from the ICU website and the Cost of Living from numbeo. Use to scrap the website and collect data.

A web server

## Installation

You will have to create .env file.

To do so, copy *.env.sample* and provide all the information asked in the file.

Execute the command : 

    $ composer install

Before being able to store any call you will have to create a database named *tekfeed_storage*

To init the tables you can just access the page : /seed of the api, it will create the table.

# Usage
## Setup endpoints
### /seed
See all the databases and create tables
| Parameter | Usage | Description|
|--|--|--|
| key | &key= | Your api Key defined in your .env|
### /clear
Delete all the table and so all the previously collected data
| Parameter | Usage | Description|
|--|--|--|
| key | &key= | Your api Key defined in your .env|

### /search
Search on the ICU ranking website for the university name in the Query parameter.
Should not be used too much to limit Google CustomSearch cost, can also be block with the LOCK variable in `.env`
| Parameter | Usage | Description|
|--|--|--|
| key | &key= | Your api Key defined in your .env|
| query | &q= | The name of the University you want to find|
## Data endpoints
### /ranking
Get all the known ranks for the Epitech available university
| Parameter | Usage | Description|
|--|--|--|
| type | &type= | The type of ranking you want, currently available : SHANGHAI or ICU|
### /universities
Get all the known universities available for Epitech 4th year.
### /col
Get the Cost of Living for all the countries known. 

# Changing Database Name
You can change the Database Name directly in 

***bootstrap.php***