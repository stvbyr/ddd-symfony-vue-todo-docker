FROM node:lts-alpine

# Create and define the node_modules's cache directory.
RUN mkdir /var/node_cache
WORKDIR /var/node_cache
COPY ./frontend/package.json ./
RUN npm install

WORKDIR /var/www

COPY ./frontend/ ./

RUN chown -R node:node .
USER node

EXPOSE 3000

CMD [ "npm", "run", "dev" ]
