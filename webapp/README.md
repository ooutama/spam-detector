# SPAM Detector Web Application
> This is a Laravel application. For more information, visit the official website of [Laravel](https://laravel.com/docs/) 

## Prerequisites
1. [PHP](https://www.php.net/downloads)
2. SQL Database, for example [MySQL](https://www.mysql.com/downloads/)
3. [Composer](https://getcomposer.org/download/):  Dependency Manager for PHP

NOTE: You can have both PHP and MySQL just by downloading [XAMPP](https://www.apachefriends.org/download.html) or [Laragon](https://laragon.org/download/index.html).

## Run the application
- Installing all the necessary requirements
```bash
$ composer install
$ composer update
``` 

- Generate .env file
```bash
$ composer run-script post-root-package-install
```

- Generate the secret key. (Encryption sensitive data)
```bash
$ composer run-script post-create-project-cmd
```

- Update .env file with your database credentials.
```env
# .env
...
DB_HOST=HERE
DB_PORT=HERE
DB_DATABASE=HERE
DB_USERNAME=HERE
DB_PASSWORD=HERE
...
```

- Migrate tables to the database 
```bash
$ php artisan migrate
```

- Run the serve
```bash
$ php artisan serve
```

- Et voila, now you can visit the application by typing _http://localhost:8000_ in your browser. 