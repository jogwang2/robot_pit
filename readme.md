# robot_pit
 PHP-Laravel Exercise
This is a PHP-Laravel Exercise which creates an api for fighting robots.

Pre-requisites (latest version of the following)
1. PHP 
2. Laravel
3. MySQL/MariaDB/PostgreSQL/SQLite

Deploy on Local Machine
1. Download or clone this project
2. Start MySQL service
3. In the root of your project, edit .env and set the database information. See below example. Make sure to set it right i.e. it exists.
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=robotpit
        DB_USERNAME=root
        DB_PASSWORD=
4. In the root of your project, run the commands below. This will create the required tables and triggers
        php artisan migrate
        php artisan passport:client --personal
5. Then run the command below to start the api service
        php artisan serve
        
API Documentation
https://app.swaggerhub.com/apis/jogwang2/RobotPit/1.0.0

Test in Postman
1. Open Postman app
2. Go to Import
3. Choose Import from Link
4. In the text field copy paste the link below, and click Import button
https://www.getpostman.com/collections/6eff86165935e6eea642
