FROM node:14

WORKDIR /usr/src/app
COPY src/. ./
RUN npm install

EXPOSE 8000

CMD ["node", "app.js"]
