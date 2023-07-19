# (x-)anom 
(another MVC framework)

An open-source, fast, super-light, secure, full-featured, modern, easy to setup, easy to learn,
highly extendable, php 8+, OOP MVC framework for the php-developer.



## Why (x-)anom?

* fast and secure
* modern, super light and elegant
* easy to setup, to learn, to optimize
* extendable + TODO: scalable
* ready for any server environment (metal, VM, container)



## Requirements

* php 8.1+
* web server (apache configuration included; nginx to come)
* latest MySQL or MariaDB (postgreSQL in development)



## Recommended

* composer
* git



## installation

Before installing onto a production environment you need to pre-cech several settings

### pre-Check

(1)   
There are several ```composer*.json``` versions;    
the  ```~./composer.json``` is the only one you actually need;   
all others found under ```~./docker/config/composer-*,json``` are for testing/
case-study puproses; so make sure that ```~./composer.json``` is updated with
all required packages
 
(2)   
When deploying on PRODUCTION make sure that all development routes are deleted
and debuging messages are disabled.

(3)    
Anom pre-sets-up two writable folders:
* ```~/storage``` (pre-public directory) ideal for file-sessioning, file-caching;
also ideal for file-uploading when serve permition is needed;
* ```~/public/files``` (post-public folder) which is ideal for public access content
Make sure to keep the one that suits your needs (or both if needed);


### Installation and Running on bare-metal or VPS

* dowload and copy directory structure an a directory
* install and setup third party  tools (ex. composer, redis, database)
* crete a virtual-host; public HTML folder shall point to the ```~/public``` direcotry
* enable needed apache-modules and your virtual host
* restart web-server


### Installation and Running on Docker 


#### Build

    docker build -t x-anom .

Force build without using cache:

    docker build --no-cache -t x-anom .



#### Run

    docker-compose up

Force rebuild; then run:

    docker-compose up --build



#### Remove builded containers

    docker-compose down




## TODO: build and deploy

build and deply instructions for various plarforms (GCloud, Azure, etc)

* Staging (Build / Deploy)
* Production (Build / Deploy)


! Run the above commands ONLY when you need to manually deploy the app to Cloud Run, otherwise everything is automated using Cloud Builds




#### While developing...

By default autoloading is handled by Composer (this is highly recommended).
While developing your application (or the framework itself) you will need to
update the autoloading of your new classes. To do so, attach a shell, change
to ```/var/www``` directory and run ```composer dump-autoload```

    cd /var/www

    composer dump-autoload

If Composer is not an option and you use the framework's autoload implementation
(enabling ```define('AUTOLOADER' , '../core/helpers/autoload.php');```),
do not forger to update the (new) paths to your new classes in the ```CLASSPATHS```
array . Both ```AUTOLOADER``` and ```CLASSPATHS``` definitions can be found
into ```core/confing/anom_settings.php``` file.




#### XDebug

anom comes with most configuration of XDebug ready; all you need is

1. on Doockerfile: uncoomment the commands under XDebug section

2. on docker-compose.yml: uncomment the services.web.extra_hosts section

3. you may need configure your ide to listen onto XDebuger host port

example: 
for vscode you need to add a ~./.vscode/launch.json file like this:

    {
        "version": "0.2.0",
        "configurations": [
            {
                "name": "Listen for Xdebug",
                "type": "php",
                "request": "launch",
                "port": 9003,
                "pathMappings": {
                    "/var/www/public": "${workspaceRoot}/public"
                }
            }
        ]
    }




## Customization

The project includes several almost-ready-to-use technologies;
You can enable / customize / disable / configure all these with a few edits.

Each time you change the configuration of your environment, do not forget to set the
related parameters on the main configuration file (```/core/config/anom_settings.php)```
and/or define environmental variables or credentials to the ```/core/auth/.env``` file.

NOTE: #1
We assume that you use composer; if not, then whatever composer does you have to
make it manually (copy dependency files and setup the autoloader's paths).

NOTE: #2
There is no need to use both Redis and Memcached, thus I have not test if these
two caching technologies will work together using the proposed comfiguration.



### Redis

To enable Redis...

Onto docker-compose.yml:

1. Uncomment the lines of 'redis' section; Your Redis-server host name will be the one that is specified in the container_name field (default: redis)

2. Uncomment the networks subsections; web-server and Redis-server will communicate through a bridge network

3. With composer:

Include predis/redis into your project's requirements using ```/docker/config/composer-redis.json```
as your composer.json file -or- install it via:

    composer require predis/predis



### Memcached

To host the Memcached service:

1. Onto docker-compose.yml: uncomment the lines of 'memcached' section; Memcached-server host name will be the one that is specified in the container_name field (default: anomemcached)

2. Onto Dockerfile: uncomment the lines under subsection 'Install Memcached'



### TODO: configutations

* [set web ports and other options witn an .env file](https://stackoverflow.com/questions/52664673/how-to-get-port-of-docker-compose-from-env-file)

* Maria database (setup instructions)

* Adminer instructions on how to setup as a Database adminitration helper; [check this!](https://stackoverflow.com/questions/33631085/how-to-use-adminer-with-composer-autoload-php)

* phpMyAdmin; configuring instuctions or setup phpMyAdmin as a docker database helper

* Apache; advanced configuration options

* nginx; setup nginx as the default web-server

* Opcache and JIT



## Notes

* '~./' refers to the root of the project

* Furhter documentation about the framework can be found in ~./core/README.md
