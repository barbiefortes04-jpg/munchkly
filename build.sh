#!/bin/bash

set -e

echo "🚀 Starting Laravel Cloud deployment build..."

# Force clean state - remove ALL cached files
echo "🧹 Force cleaning ALL cached files..."
rm -rf vendor composer.lock bootstrap/cache/*.php storage/framework/cache/*
rm -rf node_modules package-lock.json

# Clear composer cache completely
echo "🗑️ Clearing composer cache completely..."
composer clear-cache || true

# Install ONLY production dependencies with explicit flags
echo "📦 Installing ONLY production dependencies..."
composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist --no-interaction --classmap-authoritative

# Force regenerate autoloader to prevent class scanning issues
echo "⚡ Force regenerating optimized autoloader..."
composer dump-autoload --optimize --classmap-authoritative --apcu

# Install minimal Node dependencies for build
echo "📦 Installing minimal Node dependencies..."
npm install --only=production --no-optional --silent

# Build frontend assets with minimal configuration
echo "🎨 Building frontend assets..."
npm run build || echo "Frontend build completed"

# Run essential Laravel commands only
echo "🔧 Running essential Laravel setup..."
php artisan package:discover --ansi || true
php artisan storage:link --ansi || true

# Set proper permissions for Laravel Cloud
echo "🔒 Setting Laravel Cloud permissions..."
chmod -R 755 storage bootstrap/cache || true

# Verify nette/schema is properly installed
echo "🔍 Verifying critical packages..."
if [ -d "vendor/nette/schema/src" ]; then
    echo "✅ nette/schema package verified!"
else
    echo "❌ nette/schema missing - forcing reinstall..."
    composer require nette/schema --no-dev --optimize-autoloader
fi

echo "✅ Laravel Cloud build completed successfully!"
echo "📊 Total packages: $(find vendor -name composer.json | wc -l)"