####Installation
To install the BBS API, first run `composer install` from the project root directory.

After this process completes, ensure that you have a `.env` in the project root folder. If not, clone `.env.production` or `.env.local` into `.env`.

To ensure the database has the required tables, and to set up the default admin account, run the `php artisan migrate` and the `php artisan db:seed` commands.

Finally, ensure that your server points to the `\public` folder, not to the root folder of this application.

####Running locally
In the installation process, use `.env.local` and clone this to `.env`. Running the database migrations and database seeds is still required as well, however with the local configuration, the application will store all of its data into the `database\database.sqlite` database.

To start the local test application, run `php -S localhost:8080 -t public` in the project root directory. Keep the console window open, as closing it will kill the local server. The window will also display requested resources from clients. If you've started the application using the above command, you'll be able to access it on [http://localhost:8080](http://localhost:8080). This includes sending requests to it. Loading up the root page should present you with json data that looks like `{'message':'It works!'}`

#### API Documentation
Follows later
