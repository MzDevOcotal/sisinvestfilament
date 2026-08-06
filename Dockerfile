# --- Etapa 1: Compilación de Assets (Frontend con Node.js) ---
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build

# --- Etapa 2: Entorno de Producción (PHP 8.2 + Apache + SQLite) ---
FROM php:8.2-apache

# Instalar dependencias del sistema con reintentos para evitar bloqueos puntuales
RUN apt-get clean && \
    apt-get update -o Acquire::Retries=3 && \
    apt-get install -y --no-install-recommends \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    libzip-dev \
    libicu-dev \
    unzip \
    zip \
    git \
    curl

# Instalar extensiones de PHP requeridas por Laravel y Filament
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_sqlite zip intl

# Habilitar mod_rewrite de Apache para las rutas de Laravel
RUN a2enmod rewrite

# Instalar extensiones de PHP requeridas por Laravel y Filament
RUN docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código del proyecto desde tu computadora
COPY . .

# Copiar los assets ya compilados desde la Etapa 1 (Vite / Filament)
COPY --from=frontend /app/public/build ./public/build

# Instalar Composer para las dependencias de PHP de producción
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Dar permisos a storage, cache y a la carpeta de la base de datos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Asegurar que el archivo sqlite en particular tenga permisos de escritura si ya existe en tu repo
RUN touch /var/www/html/database/database.sqlite && chmod 664 /var/www/html/database/database.sqlite

# Cambiar el DocumentRoot de Apache para que apunte a la carpeta public de Laravel
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80