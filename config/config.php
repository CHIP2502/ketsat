<?php
declare(strict_types=1);

return [
    'app_name' => 'Két sắt Việt Tiệp',
    'base_path' => dirname(__DIR__),
    'database_path' => dirname(__DIR__) . '/database/app.sqlite',
    'upload_dir' => dirname(__DIR__) . '/uploads',
    'upload_url' => 'uploads',
    'admin_username' => getenv('ADMIN_USERNAME') ?: 'admin',
    'admin_password' => getenv('ADMIN_PASSWORD') ?: 'ChangeMe123!',
    'hotlines' => [
        ['label' => 'Hà Nội', 'number' => '0983 115 686', 'tel' => '0983115686'],
        ['label' => 'Hà Nội', 'number' => '0392 699 914', 'tel' => '0392699914'],
    ],
];
