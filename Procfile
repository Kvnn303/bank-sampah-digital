release: cd "Web Admin" && php artisan migrate:fresh --force && php artisan storage:link && php artisan config:cache && php artisan route:cache
web: cd "Web Admin" && php -S 0.0.0.0:${PORT:-8000} public/index.php
