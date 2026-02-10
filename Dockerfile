# 1️⃣ Imagen base: PHP 8.2 + Apache
FROM php:8.2-apache

# 2️⃣ Instalar dependencias del sistema y extensiones PHP necesarias para Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 3️⃣ Copiar todo el proyecto al contenedor
COPY . /var/www/html

# 4️⃣ Configurar permisos y habilitar mod_rewrite
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# 5️⃣ Instalar dependencias de Laravel
# --no-interaction evita prompts interactivos
# --optimize-autoloader optimiza autoload
RUN composer install --no-interaction --optimize-autoloader

# 6️⃣ Copiar .env si no existe
RUN if [ ! -f /var/www/html/.env ]; then cp /var/www/html/.env.example /var/www/html/.env; fi

# 7️⃣ Ejecutar migraciones y seeders solo si quieres reiniciar DB
# ⚠️ Solo usar migrate:fresh si no hay datos importantes
RUN php artisan migrate:fresh --seed

# 8️⃣ Exponer puerto 80
EXPOSE 80

# 9️⃣ Arrancar Apache
CMD ["apache2-foreground"]
