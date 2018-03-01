## BBS JSON-API

#### What does it do?
This project provides the server-side things to run a BBS on, and is based on a JSON api. it includes:
 * Users and Admins
 * Boards
 * Threads nested under boards
 * Posts placed in Threads
 
The API uses an api_token based authentication system, and includes configurable rate limiting.
 
Users can sign up with a username and password, then retrieve a token that their clients can use to access the BBS. Tokens can be renewed and retrieved with the username-password combo. Furthermore, there is some scoping build in to block users to modify something they did not create, admins being an exception to this. Admins can do anything, including elevating users to admins.

By default, users can be created by anyone (including non-registered users), and there is **NO** email authentication for this as of yet. 

#### What does it not do?
Well, to keep it simple, here's a few of the most common things you'd expect but that it doesn't do:
 * Images or anything else besides text
 * Provide a fancy visual interface
 * Require users to verify their accounts
 * Let users reset their passwords (I know it's sad, but you'll have to hang onto your password here man)
 * Provide notifications of any kind
 * Make you coffee
 
#### Then, what's it for?
Honestly, it's mostly a fun project for me. But it could be an easy tool to test and practice building REST-based apps on any platform that lets you use a REST api. An example client written in JavaScript will probably be found in one of my repositories later as well.

#### OK, so I want this. How do i use it?
Keep reading pal.

#### Installation
To install the BBS API, first run `composer install` from the project root directory.

After this process completes, ensure that you have a `.env` in the project root folder. If not, clone `.env.production` or `.env.local` into `.env`.

To ensure the database has the required tables, and to set up the default admin account, run the `php artisan migrate:refresh --seed` command. This will reset and populate the database with some default data:

 * An admin user, using the unsafely stored admin-admin username and password
 * A Board that's owned by the admin
 * A Thread that's owned by the admin, nested under the previously created board
 * A Post that's owned by the admin, nested under the previously generated thread.

Finally, ensure that your server points to the `\public` folder, not to the root folder of this application.

#### Running locally
In the installation process, use `.env.local` and clone this to `.env`. Running the database migrations and database seeds is still required as well, however with the local configuration, the application will store all of its data into the `database\database.sqlite` database.

To start the local test application, run `php -S localhost:8080 -t public` in the project root directory. Keep the console window open, as closing it will kill the local server. The window will also display requested resources from clients. If you've started the application using the above command, you'll be able to access it on [http://localhost:8080](http://localhost:8080). This includes sending requests to it. Loading up the root page should present you with json data that looks like `{'message':'It works!'}`

#### API Documentation
Follows later

#### TODO
 * Make user registration easier to lock down to users or admins only
 * Write API documentation
 * ...
