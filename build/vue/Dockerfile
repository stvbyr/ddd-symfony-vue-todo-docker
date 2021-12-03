FROM node:lts-alpine

# make the 'app' folder the current working directory
WORKDIR /var/www

# copy both 'package.json' and 'package-lock.json' (if available)
COPY ./frontend/ ./

# install project dependencies
RUN yarn install

EXPOSE 3000

CMD [ "npm", "run", "serve" ]
