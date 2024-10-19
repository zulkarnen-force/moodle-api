# Dockerfile
FROM php:8.0-cli

# Set the working directory in the container
WORKDIR /app

RUN apt update -y && apt install -y curl libcurl4-openssl-dev pkg-config libssl-dev
# Install dependencies
RUN docker-php-ext-install curl

COPY . .

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
