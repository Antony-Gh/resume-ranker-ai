#!/bin/bash

# Resume Ranker AI - Development Environment Setup Script
# This script sets up the development environment for the Resume Ranker AI application.

# Print colored output
print_style() {
    if [ "$2" == "info" ]; then
        COLOR="96m"
    elif [ "$2" == "success" ]; then
        COLOR="92m"
    elif [ "$2" == "warning" ]; then
        COLOR="93m"
    elif [ "$2" == "danger" ]; then
        COLOR="91m"
    else
        COLOR="0m"
    fi

    STARTCOLOR="\e[$COLOR"
    ENDCOLOR="\e[0m"
    printf "$STARTCOLOR%b$ENDCOLOR" "$1"
}

# Check if .env file exists
if [ ! -f ".env" ]; then
    print_style "Creating .env file...\n" "info"
    cp .env.example .env
else
    print_style ".env file already exists.\n" "warning"
fi

# Install PHP dependencies
print_style "Installing PHP dependencies...\n" "info"
composer install

# Generate application key
print_style "Generating application key...\n" "info"
php artisan key:generate

# Install Node.js dependencies
print_style "Installing Node.js dependencies...\n" "info"
npm install

# Build frontend assets
print_style "Building frontend assets...\n" "info"
npm run dev

# Run database migrations
print_style "Would you like to run database migrations? (y/n): " "warning"
read run_migrations

if [ "$run_migrations" == "y" ] || [ "$run_migrations" == "Y" ]; then
    print_style "Running database migrations...\n" "info"
    php artisan migrate
else
    print_style "Skipping database migrations.\n" "warning"
fi

# Seed the database
print_style "Would you like to seed the database? (y/n): " "warning"
read seed_database

if [ "$seed_database" == "y" ] || [ "$seed_database" == "Y" ]; then
    print_style "Seeding the database...\n" "info"
    php artisan db:seed
else
    print_style "Skipping database seeding.\n" "warning"
fi

# Generate API documentation
print_style "Would you like to generate API documentation? (y/n): " "warning"
read generate_docs

if [ "$generate_docs" == "y" ] || [ "$generate_docs" == "Y" ]; then
    print_style "Generating API documentation...\n" "info"
    php artisan l5-swagger:generate
else
    print_style "Skipping API documentation generation.\n" "warning"
fi

# Set storage permissions
print_style "Setting storage permissions...\n" "info"
chmod -R 777 storage
chmod -R 777 bootstrap/cache

print_style "Setup complete!\n" "success"
print_style "Run 'php artisan serve' to start the development server.\n" "info"
