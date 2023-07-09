!/bin/bash -x

# optimize performance or development

if test ["$1" == "PRODUCTION"] ; then 

    docker-php-ext-install opcache
    # copy OPcache parametres
    # etc...

    # install and enable JIT

    # else 
    # maybe install some development tools
    # like XDebug

fi