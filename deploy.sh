#!/bin/bash

echo "🚀 Starting deployment process..."

# Variables
APP_ENV="production"
APP_DEBUG="false"
CACHE_DRIVER="redis"
SESSION_DRIVER="redis"
QUEUE_CONNECTION="redis"

echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

echo "🔧 Setting up environment..."
cp .env.example .env
sed -i "s/APP_ENV=local/APP_ENV=$APP_ENV/g" .env
sed -i "s/APP_DEBUG=true/APP_DEBUG=$APP_DEBUG/g" .env
sed -i "s/CACHE_DRIVER=file/CACHE_DRIVER=$CACHE_DRIVER/g" .env
sed -i "s/SESSION_DRIVER=file/SESSION_DRIVER=$SESSION_DRIVER/g" .env
sed -i "s/QUEUE_CONNECTION=sync/QUEUE_CONNECTION=$QUEUE_CONNECTION/g" .env

echo "🗄️ Running database migrations..."
php artisan migrate --force

echo "🌱 Seeding database..."
php artisan db:seed --force

echo "🔑 Generating application key..."
php artisan key:generate

echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan app:optimize --optimize

echo "📦 Building assets..."
npm ci --production
npm run build

echo "🧹 Clearing old caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "🔒 Setting permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Deployment completed successfully!"
echo "🌐 Your application is now optimized for production!" 