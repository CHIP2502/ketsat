<?php
declare(strict_types=1);

$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$normalizedPath = strtolower($path);
$blockedPrefixes = ['/config/', '/src/', '/partials/', '/database/'];
foreach ($blockedPrefixes as $prefix) {
    if (str_starts_with($normalizedPath, $prefix)) {
        http_response_code(404);
        exit('Not found');
    }
}

$file = __DIR__ . $path;
if ($path !== '/' && is_file($file)) {
    return false;
}

require __DIR__ . '/index.php';
