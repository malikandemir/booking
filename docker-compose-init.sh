#!/bin/bash

# Copy .env file
cp .env.example .env

# Start Docker containers
docker-compose up -d

# Install composer dependencies
docker-compose exec app composer install

# Generate application key
docker-compose exec app php artisan key:generate

# Run database migrations and seeders
docker-compose exec app php artisan migrate:fresh --seed

echo "Application is now running at http://localhost:8080"
