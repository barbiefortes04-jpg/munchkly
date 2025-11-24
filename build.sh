#!/bin/bash

set -e

echo "🚀 Starting Munchkly build process..."

# Clear any existing vendor issues
echo "🧹 Cleaning up previous builds..."
rm -rf vendor composer.lock

# Install production dependencies
echo "📦 Installing production dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies
echo "📦 Installing Node.js dependencies..."
npm ci --only=production

# Build frontend assets
echo "🎨 Building frontend assets..."
npm run build

# Set proper permissions
echo "🔒 Setting permissions..."
chmod -R 755 storage bootstrap/cache

echo "✅ Build completed successfully!"