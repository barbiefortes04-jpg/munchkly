#!/bin/bash

# Emergency fallback build script for Laravel Cloud
# This script is designed to work even when main build fails

set -e

echo "🆘 EMERGENCY BUILD: Starting fallback deployment process..."

# Ultra-aggressive cleanup
echo "🧹 Emergency cleanup of all files..."
rm -rf vendor composer.lock node_modules package-lock.json
rm -rf ~/.composer/cache ~/.cache/composer bootstrap/cache/* 2>/dev/null || true

# Install packages one by one for maximum compatibility
echo "📦 Installing core Laravel packages individually..."

# Install only the absolute minimum packages
composer require laravel/framework:^10.0 --no-scripts --no-autoloader --no-dev
composer require laravel/sanctum:^3.0 --no-scripts --no-autoloader --no-dev  
composer require laravel/tinker:^2.0 --no-scripts --no-autoloader --no-dev

# Install nette/schema specifically to prevent the error
echo "🔧 Installing nette/schema specifically..."
composer require nette/schema:^1.3 --no-scripts --no-autoloader --no-dev

# Verify installation
if [ ! -f "vendor/nette/schema/src/Schema/Schema.php" ]; then
    echo "❌ CRITICAL: nette/schema installation failed!"
    exit 1
fi

# Now install all other dependencies
echo "📦 Installing remaining dependencies..."
composer install --no-dev --no-scripts --prefer-dist --no-interaction

# Generate autoloader last
echo "⚡ Generating autoloader..."
composer dump-autoload --optimize

# Minimal Laravel setup
echo "🔧 Minimal Laravel configuration..."
php artisan package:discover --ansi 2>/dev/null || true

# Set basic permissions
chmod -R 755 storage bootstrap/cache 2>/dev/null || true

echo "✅ Emergency build completed!"
echo "📊 Installed packages: $(composer show --installed | wc -l)"