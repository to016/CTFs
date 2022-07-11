#!/bin/bash

service cron start

sleep 2

apache2-foreground
