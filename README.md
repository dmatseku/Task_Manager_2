# Task Manager
This is a website for managing your plans in real-time. Application based on Laravel 7.
## Working website
    https://task-manager.dmatseku.pp.ua
## GitHub
    https://github.com/dmatseku/Task_Manager
## Install
#### Init commands
Firstly you need to load dependencies and build assets:

    Composer install
    npm install
    npm run dev|prod
    
#### Configuration
Next, you need to configure files `config/app.php`, `config/database.php`, and `config/mail.php`.<br>
**OR** you can copy file `.env.example` into `.env` and configure only this file.<br>
You need to define database, mail, and, additionally, application properties.

After configuration files, reset cache:

    php artisan config:cache
#### Database
Finally, you need to migrate the database:

    php artisan migrate
    
##### Done!
