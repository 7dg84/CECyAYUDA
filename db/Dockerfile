# Usa la imagen oficial de MySQL
FROM mysql:8.0.34

# Configura las variables de entorno necesarias para MySQL
ENV MYSQL_ROOT_PASSWORD=123
ENV MYSQL_DATABASE=cecyayuda
ENV MYSQL_USER=denuncia
ENV MYSQL_PASSWORD=123

# Copia el archivo SQL al directorio de inicialización de MySQL
COPY ./cecyayuda.sql /docker-entrypoint-initdb.d/

# Exponer el puerto 3306 para conexiones externas
EXPOSE 3306