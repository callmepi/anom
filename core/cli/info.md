# cli

This section is used to setup an anom micro-framework for the cli-interface.
The cli interface is commonly used to support and optimize the whole app.

A cli  script may run manualy, for example using...

    php cli-cache.php

although...

it is recommended to run periodically using the cron service:

    15 * * * * php path/to/cli-cache.php

or ...

when a docker instanse of the project fires up; for example including
the following line of code inside the docker/bin/docker-entrypoint.sh
(runs the script after 5 minutes delay to make sure that the instance
is up and running)

    at -f /var/www/core/cli/refresh-cache.php -t now +5 minutes
