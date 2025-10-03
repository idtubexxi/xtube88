# Install Heroku CLI

curl https://cli-assets.heroku.com/install.sh | sh

# Login

heroku login

# Create app

heroku create your-app-name

# Add buildpack PHP & Node

heroku buildpacks:add heroku/php --app your-app-name
heroku buildpacks:add heroku/nodejs --app your-app-name
heroku buildpacks --app your-app-name

# Add database (ClearDB MySQL - Free)

heroku addons:create cleardb:ignite

# Get database URL

heroku config | grep CLEARDB_DATABASE_URL

# Set environment variables

heroku config:set APP_KEY=$(php artisan key:generate --show) --app xtube
heroku config:set APP_ENV=production --app xtube
heroku config:set APP_DEBUG=false --app xtube
heroku config:set LOG_CHANNEL=errorlog --app xtube

# Deploy

git add .
git commit -m "Ready for Heroku"
git push heroku main

# Run migrations

heroku run php artisan migrate --force
