#!/bin/bash
# DashSpan App Reset Script

php artisan config:clear
php artisan route:clear

rm -rf .env
rm -rf database/database.sqlite
rm -rf storage/installed
rm -rf storage/logs/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*
rm -rf bootstrap/cache/*
rm -rf vendor

cp tools/env_orig .env
touch database/database.sqlite
composer install
php artisan optimize --force

chmod 775 storage/framework
chmod 775 storage/logs
chmod 775 bootstrap/cache
