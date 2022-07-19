# syntax=docker/dockerfile:1

FROM node:18-alpine

ENV NODE_ENV=production

WORKDIR /app

COPY ["package.json", "package-lock.json*", "./"]

RUN npm install --production

COPY index.js index.js

COPY Dockerfile Dockerfile

ARG FLAG_FILENAME

COPY flag.txt $FLAG_FILENAME

EXPOSE 3000

CMD ["node", "index.js", "server"]