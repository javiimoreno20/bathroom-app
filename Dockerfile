# Usamos PHP 8.2 con Apache
FROM php:8.2-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar todo el proyecto al contenedor
COPY . /var/www/html

# Configurar permisos y habilitar rewrite
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Instalar dependencias de Laravel
RUN composer install

# Copiar archivo .env.example como .env (si no existe)
RUN if [ ! -f /var/www/html/.env ]; then cp /var/www/html/.env.example /var/www/html/.env; fi

 7️⃣ Ejecutar migraciones y seeders **solo si quieres reiniciar la DB en cada deploy**
# ⚠️ Solo usar migrate:fresh si no tienes datos importantes
RUN php artisan migrate:fresh --seed

# Exponer puerto 80
EXPOSE 80

# Comando para arrancar Apache
CMD ["apache2-foreground"]
