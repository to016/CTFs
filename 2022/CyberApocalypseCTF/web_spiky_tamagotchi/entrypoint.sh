#!/bin/ash

# Secure entrypoint
chmod 600 /entrypoint.sh

# Initialize & Start MariaDB
mkdir -p /run/mysqld
chown -R mysql:mysql /run/mysqld
mysql_install_db --user=mysql --ldata=/var/lib/mysql
mysqld --user=mysql --console --skip-name-resolve --skip-networking=0 &

# Wait for mysql to start
while ! mysqladmin ping -h'localhost' --silent; do echo "mysqld is not yet alive" && sleep .2; done

# admin password
PASSWORD=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 16 | head -n 1)

# create database
mysql -u root << EOF
CREATE DATABASE spiky_tamagotchi;

CREATE TABLE spiky_tamagotchi.users (
  id INT AUTO_INCREMENT NOT NULL,
  username varchar(255) UNIQUE NOT NULL,
  password varchar(255) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO spiky_tamagotchi.users VALUES
(1,'admin','${PASSWORD}');

GRANT ALL PRIVILEGES ON spiky_tamagotchi.* TO 'rh0x01'@'%' IDENTIFIED BY 'r4yh4nb34t5b1gm4c';
FLUSH PRIVILEGES;
EOF

# launch supervisord
/usr/bin/supervisord -c /etc/supervisord.conf