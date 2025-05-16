FROM nginx:stable-alpine
RUN apk update && apk upgrade
COPY ./default.conf /etc/nginx/conf.d/default.conf
# Copiar los archivos de la aplicación al contenedor
COPY . /var/www/html