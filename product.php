<?php
declare(strict_types=1);
require __DIR__ . '/src/bootstrap.php';

$slug = trim((string) ($_GET['slug'] ?? ''));
$stmt = $pdo->prepare('SELECT * FROM products WHERE slug = :slug AND is_active = 1 LIMIT 1');
$stmt->execute(['slug' => $slug]);
$product = $stmt->fetch();
if (!$product) {
    http_response_code(404);
    $pageTitle = 'Không tìm thấy sản phẩm';
    require __DIR__ . '/partials/header.php';
    echo '<section class="container empty-state"><h1>Không tìm thấy sản phẩm</h1><a class="button" href="' . url('index.php') . '">Về danh sách</a></section>';
    require __DIR__ . '/partials/footer.php';
    exit;
}

$pageTitle = $product['name'] . ' | ' . $config['app_name'];
$pageDescription = $product['short_description'];
require __DIR__ . '/partials/header.php';
?>
<section class="container product-detail">
    <a class="back-link" href="<?= url('index.php') ?>">← Quay lại danh sách</a>
    <div class="detail-grid">
        <div class="detail-image">
            <img src="<?= e(imageUrl($product, $config)) ?>" alt="<?= e($product['name']) ?>">
        </div>
        <div class="detail-content">
            <?php if ($product['category']): ?><span class="badge"><?= e($product['category']) ?></span><?php endif; ?>
            <h1><?= e($product['name']) ?></h1>
            <p class="lead"><?= e($product['short_description']) ?></p>
            <div class="detail-price"><?= e($product['price']) ?></div>
            <div class="call-panel">
                <strong>Liên hệ để được tư vấn ngay</strong>
                <?php foreach ($config['hotlines'] as $hotline): ?>
                    <a class="button button--primary button--block" href="tel:<?= e($hotline['tel']) ?>"><?= e($hotline['label']) ?> · <?= e($hotline['number']) ?></a>
                <?php endforeach; ?>
            </div>
            <ul class="benefits">
                <li>Hàng chính hãng, nguồn gốc rõ ràng</li>
                <li>Hỗ trợ giao hàng và lắp đặt</li>
                <li>Tư vấn bảo hành trực tiếp</li>
            </ul>
        </div>
    </div>
    <?php if ($product['description']): ?>
        <article class="description"><h2>Thông tin sản phẩm</h2><div><?= nl2br(e($product['description'])) ?></div></article>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
