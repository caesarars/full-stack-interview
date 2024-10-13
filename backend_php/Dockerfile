# Gunakan image dasar PHP dengan Apache
FROM php:8.2-apache

# Mengaktifkan mod_rewrite
RUN a2enmod rewrite

# Install ekstensi MySQLi dan PDO MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Salin file aplikasi PHP ke dalam container
COPY ./src/ /var/www/html/

# Ubah hak akses direktori
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 untuk HTTP
EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]
