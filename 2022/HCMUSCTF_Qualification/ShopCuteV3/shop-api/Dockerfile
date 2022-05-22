FROM node:12
WORKDIR /usr/src/app
COPY ./src .
# make a random name and move the flag outside for security purpose
RUN npm install
CMD [ "node", "server.js" ]