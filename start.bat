docker network create cecyayuda
volumes: - ./webpage/:/var/www/html - ./config/uploads.ini:/usr/local/etc/php/conf.d/uploads.ini
networks: - cecyayuda
ports: - "80:80"
volumes: - ./webpage/:/var/www/html - ./config/conf.d/:/etc/nginx/conf.d/

docker-compose up --name cecyayuda  -d --build