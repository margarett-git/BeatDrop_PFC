# Usamos la imagen base que ya teníamos
FROM php:8.2-apache

# Instalamos el driver de MySQL para que PHP pueda conectar con la base de datos
RUN docker-php-ext-install pdo pdo_mysql

# Habilitamos el módulo de reescritura de Apache (útil para el futuro)
RUN a2enmod rewrite