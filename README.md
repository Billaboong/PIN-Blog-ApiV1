# People In Need Assessment - CRUD App


## Project setup
```
composer install
```
## Configuration
### Make changes to the .env file as follows:
rename .env.example to .env
Setup database config - uncomment line 29 and provide your database credentials
### Database Migrations
To setup the database execute the following:
```
php bin/console make:migration
```
```
php bin/console doctrine:migrations:migrate
```
The above command might throw some errors but the relevant tables will be created. You can double check if the tables actually have been created
### Generate keys for use with authentication of the API
Authentication uses <a href="https://symfony.com/bundles/LexikJWTAuthenticationBundle/current/index.html">LexikJWTAuthenticationBundle</a> hence navigate to the page and generate the keys as described there. Kindly note you will not need to install it but only set it up so that in your .env file you will have its configuration as explained in the documentation page.

