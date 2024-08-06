# Gunakan image PHP 8.1 dengan FPM (FastCGI Process Manager)
FROM php:8.1-fpm

# Update package list dan install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libxml2-dev \
    libxslt1-dev \
    libicu-dev \
    libonig-dev \
    g++

# Membersihkan cache apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# Menambahkan konfigurasi PHP
RUN echo "max_execution_time=36000" >> /usr/local/etc/php/conf.d/99-custom.ini
RUN echo "max_input_time=360" >> /usr/local/etc/php/conf.d/99-custom.ini
RUN echo "upload_max_filesize=2G" >> /usr/local/etc/php/conf.d/99-custom.ini
RUN echo "post_max_size=2G" >> /usr/local/etc/php/conf.d/99-custom.ini
# Install ekstensi PHP
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Instal Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Menambahkan user untuk aplikasi Laravel (ganti User10 dengan www)
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www User10

# Mengatur direktori kerja
WORKDIR /var/www

# Menyalin isi direktori aplikasi
COPY . /var/www

# Menetapkan izin untuk direktori Laravel
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache
RUN chown -R User10:www /var/www

# Mengubah pengguna saat ini ke User10
USER User10

# Menyalin file composer.lock dan composer.json, dan menginstal dependensi PHP
COPY --chown=User10:www composer.lock composer.json /var/www/
RUN composer install

# Mengekspos port 9000 dan menjalankan server php-fpm
EXPOSE 9000
CMD ["php-fpm"]
