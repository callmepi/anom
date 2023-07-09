## (x-)anom
### X is ANOther Mvc

x-anom is an Object-Oriented MVC php-framework.

Main advantages of the framework:

* It is super-light and fast;
* Core has almost zero dependences and contains almost anything you need to start and optimize a server-based web-application.
* It is extensible especialy if you use Composer (which is recommended, though not required)
* Handles security; and as long as you write code using secure practices, it will be secure
* It's easy to configure, easy to code, easy to use



### Requirements

* PHP 8+
* Some webserver (Apache/Nginx)
* Some SQL server (MySQL/MariaDB/Postgress)
* Basic knowledge of php and SQL




### What is included

Core components:


#### Router

Routes request to the appropriate Controller
- matches static paths
- matches dynamic paths through regex expressions
- takes care of request-method


#### Controller and Model

(just write your Controller and Model classes)


#### View (rendering engine)

Usually you just need to call the render_view(template, data) function.
For templating we use the php short-tag syntax.

main functions:

* load_template( template, data)

* render_view( view_file , data )

* load_asset( type, assets_array )

* set_headers( content_type, ttl, more_array )

* render_text( data, content_type, ttl )

* reply_json( data, ttl )


#### Class autoloader

Composer is recomende; 
but if is not available this one will do the job


#### Caching

* File Caching mechanism

* Redis Cache

* Memcached Caching


#### Session Management

* DefaultSession: psevdo-handler with passthrough methods

* FileSession: custom file-based session handler

* DatabaseSession: database session handler



#### More

* Proxy design pattern

* Error handling

* Repository

* Cli interface (*summarizes anom*)





### Other features

* Docker ready

You can run the framework as is in a Docker environment;
Also. you can edit just a bit the configuration files to customize the project
to support various technologies; Check the README file in the project's root
folder to find out more.



### What is not included

* It lacks a query builder (SQL is easy and very powerful); do not forget to use prepared statements for all your SQL queries.

* TODO: an abstruct Shoping-Cart class



## Life (and death) of a Client_Request–Server_reply session

1. THE REQUEST: client makes a request -> request arives to the server -> .htaccess sends the request to the index.php

2. index loads Configuration (constants.php + config.php) and AUTOLOADER in order load classes easily

3. index loads init.php -> after initialization the APP is READY -> index loads the routes; Route::run() -> the APP is RUNNING

4. Router resolves the request pattern and calls a Controller

5. (if needed) Controller asks data from the Model; then responds a reply to the client using **render** functions -> APP dies



## Direcrtory structure

NOTE: directory structure needs updata, but the main structure ramains untouched.

    .
    |-- container               * for docker/container configuration
    |   |-- bin                     * scripts
    |   `-- config                  * settings
    |
    |-- core                    * core code
    |   |-- auth                    * authentication and credentials
    |   |
    |   |-- config                  * application parametres
    |   |   |-- config.php
    |   |   |-- constants.php
    |   |   |-- credentials.php
    |   |   `-- init.php
    |   |
    |   |-- classes                 * core classes
    |   |   |-- cacher    
    |   |   |-- Benchmark.php
    |   |   |-- Database.php
    |   |   |-- Route.php
    |   |   |-- Security.php
    |   |   `-- Session.php
    |   |
    |   `-- helpers                 * core helpers
    |       |-- autoload.php            
    |       |-- error-handling.php
    |       `-- render.php
    |
    |-- data                    * folder for batch data-imports to database
    |
    |-- public                  ** PUBLIC directory
    |   |-- app                     * APP
    |   |   |-- Controllers             * Controllers
    |   |   |   |-- Art.php
    |   |   |   `-- ...
    |   |   |
    |   |   |-- Models                  * Models
    |   |   |   |-- Art_model.php
    |   |   |   `-- ...
    |   |   |
    |   |   |-- Views                   * Views
    |   |   |   |-- group.php
    |   |   |   `-- item.php
    |   |   |
    |   |   `-- routes.php              * application routes
    |   |
    |   `-- cache                   * Caching folder
    |
    `-- vendor              * Vendor classes and autoloader
        |-- composer
        `-- ...




## Naming Conventions and good practices

1. Keep Controllers, Models, Views in their folders

2. Organize View elements in subfolders

3. Controller and Model names shall be camelcased;
   - fist letter should be Uppercase;
   - classes shall be named exactly as their filenames

4. Model names shall be suffixed with _model

5. Comment every-single Controller/Model/class and comment every method

6. On Views (php templates) use php short-tags when possible
   - Views are about rendering data; avoid complex-logic
   - [if/else], [foreach] and some flag/temp [variables] sould be fair enough

7. All of the above rules are strongly recommended (although not obligatory);



## Notes and brainstorming

### for template system check
    https://css-tricks.com/php-is-a-ok-for-templating/

### for rendering system you may check volt too
    https://docs.phalcon.io/4.0/en/volt



## Brainstorming

* take care of various hacks
    chk: https://stackoverflow.com/questions/1996122/how-to-prevent-xss-with-html-php

* HTML to MarkDown!
    chk: https://github.com/thephpleague/html-to-markdown





VIEW data

-> ceo  -> title
        -> description
        -> keywords
        -> ...

-> content  -> view : view_filename
            -> key  : some_variable_name
            -> data : the_data




## Knowledge Requirements 

## Tools of work

None of the following tools is necessary, but they will help very very-much.

- Composer

- Docker

- Code editor

- Coffee + Nicotine
