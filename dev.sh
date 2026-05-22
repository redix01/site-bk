#!/bin/bash

# Development script for Obsidian Wealth Admin Dashboard
echo "🚀 Starting Obsidian Wealth Development Environment..."

# Check if node_modules exists
if [ ! -d "node_modules" ]; then
    echo "📦 Installing npm dependencies..."
    npm install
fi

# Build assets
echo "🔨 Building assets..."
npm run build

# Start the development server
echo "🌐 Starting Laravel development server..."
echo "📊 Admin Dashboard: http://localhost:8000/admin"
echo "👤 Admin Login: admin@obsidianwealths.com / password"
echo ""
echo "Press Ctrl+C to stop the server"
php artisan serve --host=0.0.0.0 --port=8000
