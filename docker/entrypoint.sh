#!/usr/bin/env sh
set -eu

# Run required setup each boot. storage:link can fail when the link already exists.
php artisan migrate --force
php artisan storage:link || true

exec "$@"
