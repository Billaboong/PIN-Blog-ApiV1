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

## Serve the application
```
symfony serve:start
```

## API Documentation
<a href="https://app.swaggerhub.com/apis/MAGEROIAN/people-in_need_blog_api/1.0.0">People In Need - Blog API</a>

<b>User</b>

- POST /register: Create a user to be used for authentication
- POST /login_check: Generate a token to use as to grant access to protected endpoints

<b>Blog</b>

To access the below endpoints you will need to set the bearer token generated from /login_check endpoint

- GET /api/blogs: Returns all blogs.
- POST /api/blogs: Creates a new blogs.
- GET /api/blogs/{id}: Shows a blog with the specified ID.
- PUT /api/blogs/{id}: Updates the blogs with the specified ID.
- DELETE /api/blogs/{id}: Deletes the blogs with the specified ID.
- GET /api/blogs/search/{id}: Retrieves a blogs by ID.
- GET /api/blogs/search/description/{description}: Retrieves blogs which have texts as the description.

