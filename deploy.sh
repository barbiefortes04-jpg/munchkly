#!/bin/bash
# Munchkly Production Deployment Script

echo "🚀 Starting Munchkly deployment..."

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev

# Install Node.js dependencies and build assets
echo "🎨 Building frontend assets..."
npm ci
npm run build

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Clear and cache configurations
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
echo "🔗 Creating storage symlink..."
php artisan storage:link

echo "✅ Munchkly deployment completed successfully!"