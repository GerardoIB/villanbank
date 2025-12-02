# Imagen de PHP + Apache
FROM php:8.2-apache

# Habilitar módulos de Apache necesarios
RUN a2enmod rewrite headers

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Configurar PHP para mostrar errores (TEMPORALMENTE para debug)
RUN echo "display_errors = On" >> /usr/local/etc/php/php.ini-production \
    && echo "display_startup_errors = On" >> /usr/local/etc/php/php.ini-production \
    && echo "error_reporting = E_ALL" >> /usr/local/etc/php/php.ini-production \
    && cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Configurar Apache para permitir .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Configurar Apache para seguir symlinks
RUN echo '<Directory /var/www/html/>' >> /etc/apache2/apache2.conf \
    && echo '    Options Indexes FollowSymLinks' >> /etc/apache2/apache2.conf \
    && echo '    AllowOverride All' >> /etc/apache2/apache2.conf \
    && echo '    Require all granted' >> /etc/apache2/apache2.conf \
    && echo '</Directory>' >> /etc/apache2/apache2.conf

# Copiar archivos al servidor
COPY . /var/www/html/

# Dar permisos correctos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Habilitar logs de error de Apache
RUN echo "ErrorLog /var/log/apache2/error.log" >> /etc/apache2/apache2.conf \
    && echo "CustomLog /var/log/apache2/access.log combined" >> /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2-foreground"]