FROM php:8.4-apache

# Instalar dependências
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache
RUN a2enmod rewrite

# Configurar VirtualHost
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Copiar arquivos do projeto
COPY . /var/www/html

# Remover vendor existente e limpar
RUN rm -rf /var/www/html/vendor \
    && rm -rf /var/www/html/composer.lock

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache \
    && mkdir -p /var/www/html/storage/tmp \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/framework/cache \
    && chmod -R 777 /var/www/html/storage/tmp

WORKDIR /var/www/html

# Instalar dependências (agora limpo)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Cachear configurações
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Rodar migrations automaticamente
RUN php artisan migrate --force

EXPOSE 80