#!/bin/bash
docker rm -f web_genesis_wallet
docker build -t web_genesis_wallet .
docker run --name=web_genesis_wallet --rm -p1337:80 -it web_genesis_wallet
