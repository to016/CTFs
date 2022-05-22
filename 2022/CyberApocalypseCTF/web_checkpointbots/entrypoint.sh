#!/bin/bash

# Secure entrypoint
chmod 600 /entrypoint.sh

# Initialize & Start MariaDB
mkdir -p /run/mysqld
chown -R mysql:mysql /run/mysqld
mysqld --user=mysql --console --skip-name-resolve --skip-networking=0 &

# Wait for mysql to start
while ! mysqladmin ping -h'localhost' --silent; do echo "mysqld not up yet" && sleep .2; done

mysql -u root << EOF
CREATE DATABASE checkpointbot_db;

CREATE USER 'checkpoint_user'@'%' identified by 'ch3ckp0in7rh0x01';

GRANT ALL PRIVILEGES ON checkpointbot_db.* TO 'checkpoint_user'@'%';
FLUSH PRIVILEGES;
EOF

/usr/bin/supervisord -c /etc/supervisord.conf