release: php artisan migrate --force && php artisan storage:link --force 2>/dev/null || true
web: php -S 0.0.0.0:${PORT:-8000} public/index.php
