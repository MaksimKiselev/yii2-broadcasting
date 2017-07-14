FROM node:8.1.4

RUN npm install -g laravel-echo-server --unsafe-perm

COPY ./laravel-echo-server.json /root
ADD ./entrypoint.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]