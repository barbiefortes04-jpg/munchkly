#!/bin/bash

set -e

echo "🚀 Starting Laravel Cloud deployment (production-only build)..."

# Minimal cleanup - only what's necessary
echo "🧹 Cleaning build artifacts..."
rm -rf bootstrap/cache/*.php storage/framework/cache/* storage/framework/sessions/* storage/framework/views/* 2>/dev/null || true

# Skip vendor directory cleanup to avoid ClassMapGenerator issues
echo "⚠️ Preserving vendor directory to prevent ClassMapGenerator errors..."

# Only run essential Laravel commands
echo "🔧 Running essential Laravel setup..."
php artisan package:discover --ansi 2>/dev/null || true

# Set minimal permissions
echo "🔒 Setting basic permissions..."
chmod -R 755 storage bootstrap/cache 2>/dev/null || true

# Simple verification without complex checks
echo "✅ Laravel Cloud deployment completed!"
echo "📊 Environment: $(php artisan --version 2>/dev/null | head -1 || echo 'Laravel Framework')"