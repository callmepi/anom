#! /bin/sh

# setup writable directories
# ------------------------------------------------------------------------------
# grand to storage directory writable permissions
# set storage directory to apache's user ownership
chmod -R 755 /var/www/storage
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/public/files
chmod -R 757 /var/www/public/files

# prepare auutoloading
# ------------------------------------------------------------------------------
(cd /var/www ; composer update)
(cd /var/www ; composer dump-autoload)


### set -ex
### 
### /cloud_sql_proxy -instances=pythia-251711:europe-west4:pythia-db-eu -dir=/cloudsql -credential_file=/cloudsqlproxy.json &
### sleep 3

# start apache
# ------------------------------------------------------------------------------
# End with running the original command
exec /usr/sbin/apache2ctl -D FOREGROUND
