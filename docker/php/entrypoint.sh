#!/bin/sh
set -e

# Siapkan direktori writable Laravel saat pertama kali dijalankan.
if [ -f artisan ]; then
    mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache

    # Bisa gagal di bind mount Windows/macOS, jadi jangan hentikan container.
    chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
    chmod -R ug+rwX storage bootstrap/cache 2>/dev/null || true
fi

exec "$@"
