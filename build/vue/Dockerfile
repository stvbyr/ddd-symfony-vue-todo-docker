FROM node:lts-alpine

WORKDIR /var/www

COPY ./frontend/package* ./
RUN npm install

COPY ./frontend/ ./

RUN chown -R node:node .
USER node

CMD [ "npm", "run", "dev" ]
