#!/bin/bash

# Configuration
REMOTE_HOST="tashirah"
REMOTE_DIR="/var/www/china-passports"
REPO_URL="git@github.com:alialyaghmour/china-passports.git" # Replace with actual repo URL if different

echo "Deploying to $REMOTE_HOST..."

ssh $REMOTE_HOST << EOF
    # Create directory if not exists
    if [ ! -d "$REMOTE_DIR" ]; then
        echo "Creating directory..."
        sudo mkdir -p $REMOTE_DIR
        sudo chown -R \$USER:\$USER $REMOTE_DIR
        git clone $REPO_URL $REMOTE_DIR
    fi

    cd $REMOTE_DIR
    
    echo "Pulling latest changes..."
    git pull origin main

    echo "Installing dependencies..."
    composer install --no-dev --optimize-autoloader
    npm install
    npm run build

    echo "Running migrations..."
    php artisan migrate --force

    echo "Optimizing..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    echo "Setting permissions..."
    sudo chown -R www-data:www-data storage bootstrap/cache
    sudo chmod -R 775 storage bootstrap/cache

    echo "Reloading Apache..."
    sudo systemctl reload apache2
EOF

echo "Deployment complete!"
