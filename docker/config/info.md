## About cron jobs

(on docker containers)

Dockerfile #1

    RUN apt-get update && apt-get install cron -y 
    RUN crontab /
    crontab -l | { cat; echo "10 * * * * /usr/local/bin/php /var/www/core/cli/cache-categories.php"; } | crontab -
    CMD cron



---


Dockerfile #2

    RUN apt-get update && \
        apt-get -y install tzdata cron

    RUN cp /usr/share/zoneinfo/Europe/Rome /etc/localtime && \
        echo "Europe/Rome" > /etc/timezone

    #RUN apt-get -y remove tzdata
    RUN rm -rf /var/cache/apk/*

    # Copy cron file to the cron.d directory
    COPY cron /etc/cron.d/cron

    # Give execution rights on the cron job
    RUN chmod 0644 /etc/cron.d/cron

    # Apply cron job
    RUN crontab /etc/cron.d/cron

    # Create the log file to be able to run tail
    RUN mkdir -p /var/log/cron

    # Add a command to base-image entrypont scritp
    RUN sed -i 's/^exec /service cron start\n\nexec /' /usr/local/bin/apache2-foreground


---

handle cron status

    service cron status
    service cron stop
    service cron start



---


docker folder: organizing configuration files

    docker
         |-- bin: docker-ettrypoint.sh, optimize.sh
         |
         `-- config: composer*.json opcache.ini php-overides.ini cron-jobs


---


check:
* http://www.idein.it/joomla/14-docker-php-apache-with-crontab