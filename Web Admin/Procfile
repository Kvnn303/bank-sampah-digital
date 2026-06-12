release: php artisan migrate:fresh --force && php artisan storage:link && php artisan config:cache && php artisan route:cache
web: php -S 0.0.0.0:${PORT:-8000} public/index.php
