#!/usr/bin/env bash
apt-get update
apt-get install -y zip unzip docker-compose
docker network create --gateway 10.37.13.1 --subnet 10.37.13.0/24 chongxun-network
unzip chongxun-daemon-web02-final-ascis2021.zip
cd chongxun
docker-compose up -d
#sleep 4 mins for gitlab compose done
sleep 240
#the gift for developers, disable admin's approval when sign up
docker exec -it gitlab-13.10.2-ee.0-internal gitlab-rails runner "Gitlab::CurrentSettings.update!(require_admin_approval_after_user_signup: false)"
