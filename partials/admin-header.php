<?php $flash = consumeFlash(); ?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'Quản trị') ?></title>
    <link rel="stylesheet" href="<?= asset('assets/css/app.css') ?>">
</head>
<body class="admin-body">
<div class="admin-shell">
    <aside class="admin-sidebar">
        <a class="brand" href="<?= url('index.php') ?>">KÉT SẮT <span>VIỆT TIỆP</span></a>
        <nav class="admin-menu">
            <a href="<?= url('admin/index.php') ?>">Sản phẩm</a>
            <a href="<?= url('admin/product-form.php') ?>">Thêm sản phẩm</a>
            <a href="<?= url('admin/change-password.php') ?>">Đổi mật khẩu</a>
            <a href="<?= url('index.php') ?>">Xem website</a>
            <a href="<?= url('admin/logout.php') ?>">Đăng xuất</a>
        </nav>
    </aside>
    <main class="admin-main">
        <div class="admin-mobile-top"><span>Xin chào, <strong><?= e($_SESSION['admin_username'] ?? 'admin') ?></strong></span><a href="<?= url('admin/logout.php') ?>">Đăng xuất</a></div>
        <?php if ($flash): ?><div class="alert <?= $flash['type'] === 'success' ? 'alert--success' : '' ?>"><?= e($flash['message']) ?></div><?php endif; ?>
