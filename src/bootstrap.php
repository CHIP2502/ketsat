<?php
declare(strict_types=1);

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);

$config = require dirname(__DIR__) . '/config/config.php';
require_once dirname(__FILE__) . '/database.php';
require_once dirname(__FILE__) . '/functions.php';

$pdo = db($config);
if ((int) $pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn() === 0) {
    $stmt = $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (?, ?)');
    $stmt->execute([$config['admin_username'], password_hash($config['admin_password'], PASSWORD_DEFAULT)]);
}
seedProducts($pdo, $config);
