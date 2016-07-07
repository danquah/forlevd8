#!/bin/sh

# Make sure files folders exists and are owned by www-data.
sed -i -- 's%/var/www/html%/var/www/web%g' /etc/apache2/sites-available/* /etc/apache2/conf-available/*
mkdir -p /var/www/web/sites/default/files/private
chown -R www-data /var/www/web/sites/default/files

# Make the configuration-dir world writable.
chmod 777 /var/www/web/private/configuration

# Put cron file into function.
if [ -r /etc/cron.d/cron.conf ]; then
    # We do a cp to make sure the file is owned by root (the .conf
    # file is mounted into the volume and is owned by the outside user
    # -- besides that the . in the name makes it ignore by crond).
    cp /etc/cron.d/cron.conf /etc/cron.d/cron
    chown root:root /etc/cron.d/cron
fi
