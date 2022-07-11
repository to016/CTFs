#!/bin/bash
mysql -u root -p###CENSORED### < /docker-entrypoint-initdb.d/setup.sql
