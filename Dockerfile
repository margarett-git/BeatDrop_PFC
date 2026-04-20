# Usamos la imagen base que ya tenÃ­amos
FROM php:8.2-apache

# Instalamos el driver de MySQL para que PHP pueda conectar con la base de datos
RUN docker-php-ext-install pdo pdo_mysql

# Servimos la app desde /public (estructura MVC: controllers/models/views fuera del docroot)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Habilitamos el módulo de reescritura de Apache (útil para el futuro)
RUN a2enmod rewrite
RUN sed -i "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf
