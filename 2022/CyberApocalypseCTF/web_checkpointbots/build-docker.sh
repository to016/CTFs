#!/bin/bash
docker rm -f web_checkpointbots
docker build -t web_checkpointbots .
docker run --name=web_checkpointbots --rm -p1337:1337 -it web_checkpointbots