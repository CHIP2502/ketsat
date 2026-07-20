<?php
$pageTitle = $pageTitle ?? $config['app_name'];
$pageDescription = $pageDescription ?? 'Két sắt chính hãng, tư vấn tận tâm và giao hàng toàn quốc.';
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($pageDescription) ?>">
    <link rel="stylesheet" href="<?= asset('assets/css/app.css') ?>">
</head>
<body>
<header class="site-header">
    <div class="topbar">
        <div class="container topbar__inner">
            <span>Hệ thống két sắt chính hãng</span>
            <span>Miễn phí tư vấn sản phẩm</span>
        </div>
    </div>
    <div class="container navbar">
        <a class="brand" href="<?= url('index.php') ?>">KÉT SẮT <span>VIỆT TIỆP</span></a>
        <nav class="nav-links" aria-label="Điều hướng chính">
            <a href="<?= url('index.php') ?>">Sản phẩm</a>
            <a href="#lien-he">Liên hệ</a>
        </nav>
        <div class="header-phone">
            <small>Tư vấn miễn phí</small>
            <a href="tel:<?= e($config['hotlines'][0]['tel']) ?>"><?= e($config['hotlines'][0]['number']) ?></a>
        </div>
        <a class="button button--primary header-cta" href="tel:<?= e($config['hotlines'][0]['tel']) ?>">Gọi ngay</a>
    </div>
</header>
<main>
