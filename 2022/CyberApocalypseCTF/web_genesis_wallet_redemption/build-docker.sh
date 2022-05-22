#!/bin/bash
docker rm -f web_genesis_wallet_redemption
docker build -t web_genesis_wallet_redemption .
docker run --name=web_genesis_wallet_redemption --rm -p1337:80 -it web_genesis_wallet_redemption