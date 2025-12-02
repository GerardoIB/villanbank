# Imagen de PHP + Apache
FROM php:8.2-apache

# Habilitar mod_rewrite (necesario para .htaccess)
RUN a2enmod rewrite

# Copiar archivos al servidor
COPY . /var/www/html/

# Dar permisos
RUN chown -R www-data:www-data /var/www/html

# Configurar Apache para permitir .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

EXPOSE 80

CMD ["apache2-foreground"]
