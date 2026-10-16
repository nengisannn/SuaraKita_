FROM php:8.2-apache

# Install ekstensi MySQL PDO & MySQLi yang dibutuhkan SuaraKita
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache Mod Rewrite untuk clean URL & routing
RUN a2enmod rewrite

# Copy seluruh source code ke direktori Apache
COPY . /var/www/html/

# Set permission direktori upload agar bisa ditulisi file lampiran
RUN chmod -R 777 /var/www/html/assets/uploads

# Expose port standar Render (80)
EXPOSE 80
