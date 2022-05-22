#!/bin/bash
docker rm -f web_spiky_tamagotchi
docker build -t web_spiky_tamagotchi .
docker run --name=web_spiky_tamagotchi --rm -p1337:1337 -it web_spiky_tamagotchi