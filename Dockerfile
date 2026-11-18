FROM php:8.2-apache

# Instala as dependências do SQLite e cURL para comunicação entre o App e o Site
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_sqlite curl

# Copia todos os arquivos do projeto para a pasta pública do servidor
COPY . /var/www/html/

# Garante permissão de leitura e escrita para o SQLite criar e gravar no arquivo .db
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html

EXPOSE 80