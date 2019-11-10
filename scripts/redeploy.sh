#!/usr/bin/env bash
#
# This script is intended to be run from a webhook to redeploy a demo
# or staging instance.
# NOTE: All data will be removed when redeploying the instance!

set -euo pipefail
IFS=$'\n\t'

export DIR
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

cd "$DIR/../"

# Fetch updates from the Git repository
git stash push
git pull --rebase
# We may not always have a stash available, so allow failure
git stash pop || true

# Install dependencies
composer install
yarn install --frozen-lockfile

# Build site files
yarn run prod
yarn run doc

# Run database migrations, seed test data and create a test user
php artisan migrate:refresh --seed
php artisan admin:create "demo@example.com" "demo" "Demo User"

# Cache all the things
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
