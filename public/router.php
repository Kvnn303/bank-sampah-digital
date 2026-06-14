<?php

// Built-in PHP server router for Laravel
// Routes all requests through index.php (needed for routing)

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// If this file or a matching physical file exists, serve it directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

require_once __DIR__ . '/index.php';
