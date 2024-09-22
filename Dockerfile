FROM php:8.2-apache

# Instala extensões necessárias
RUN apt-get update && apt-get install -y \
    libonig-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql mbstring \
    # Instala a extensão do Redis
    && pecl install redis \
    && docker-php-ext-enable redis

# Habilitar o mod_rewrite do Apache
RUN a2enmod rewrite

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia os arquivos composer.json e composer.lock
COPY composer.json composer.lock ./

# Instala as dependências do Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copia o restante do código da aplicação
COPY . .

# Configurar o Apache para permitir .htaccess
COPY ./apache-config.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80
