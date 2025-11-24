#!/bin/bash
# Pre-deployment script to ensure clean state

echo "🧹 Pre-deployment cleanup for Laravel Cloud..."

# Remove any problematic cached files
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/compiled.php
rm -rf storage/framework/views/*.php

# Create necessary directories
mkdir -p storage/app/public
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p bootstrap/cache

# Set proper permissions
chmod -R 755 storage bootstrap/cache

echo "✅ Pre-deployment cleanup completed!"