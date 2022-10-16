#/bin/sh

docker kill query_master 2>/dev/null
docker build . -t query_master
docker run -e -d --rm --name query_master -p 9020:9000 query_master

