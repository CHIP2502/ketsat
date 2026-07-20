<?php
declare(strict_types=1);
require dirname(__DIR__) . '/src/bootstrap.php';
$_SESSION = [];
session_destroy();
redirect('admin/login.php');
