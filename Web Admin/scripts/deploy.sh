#!/bin/bash

# Migration script untuk Railway deployment
# Menjalankan php artisan migrate:fresh --force untuk mengatasi crash loop

echo "🚀 Starting Railway deployment process..."
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

echo "🔑 Setting up environment..."
php artisan key:generate --force 2>/dev/null || true

echo "🗄️  Running migrations with fresh (dropping all tables and re-creating)..."
php artisan migrate:fresh --force --seed

echo "🧹 Clearing all caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "⚡ Re-caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment complete!"
