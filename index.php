<?php
declare(strict_types=1);
require __DIR__ . '/src/bootstrap.php';

$keyword = trim((string) ($_GET['q'] ?? ''));
$category = trim((string) ($_GET['category'] ?? ''));
$conditions = ['is_active = 1'];
$params = [];
if ($keyword !== '') {
    $conditions[] = '(name LIKE :keyword OR short_description LIKE :keyword)';
    $params['keyword'] = '%' . $keyword . '%';
}
if ($category !== '') {
    $conditions[] = 'category = :category';
    $params['category'] = $category;
}
$stmt = $pdo->prepare('SELECT * FROM products WHERE ' . implode(' AND ', $conditions) . ' ORDER BY featured DESC, id DESC');
$stmt->execute($params);
$products = $stmt->fetchAll();
$categories = $pdo->query("SELECT DISTINCT category FROM products WHERE is_active = 1 AND category != '' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

$pageTitle = 'Két sắt chính hãng | ' . $config['app_name'];
require __DIR__ . '/partials/header.php';
?>
<section class="hero">
    <div class="container hero__content">
        <p class="eyebrow">AN TOÀN CHO MỌI GIA ĐÌNH</p>
        <h1>Két sắt chính hãng, giao hàng tận nơi</h1>
        <p>Chọn sản phẩm phù hợp với nhu cầu. Đội ngũ tư vấn hỗ trợ trực tiếp trước và sau khi mua.</p>
        <a class="button button--primary" href="tel:<?= e($config['hotlines'][0]['tel']) ?>">Gọi tư vấn <?= e($config['hotlines'][0]['number']) ?></a>
    </div>
</section>

<section class="trust-strip">
    <div class="container trust-grid">
        <div class="trust-item"><span class="trust-icon">✓</span><div><strong>Chính hãng</strong><small>Nguồn gốc rõ ràng</small></div></div>
        <div class="trust-item"><span class="trust-icon">↗</span><div><strong>Tư vấn tận tâm</strong><small>Chọn đúng nhu cầu</small></div></div>
        <div class="trust-item"><span class="trust-icon">⌂</span><div><strong>Giao hàng tận nơi</strong><small>Hỗ trợ lắp đặt</small></div></div>
    </div>
</section>

<section class="container product-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">DANH MỤC SẢN PHẨM</p>
            <h2>Sản phẩm nổi bật</h2>
            <p class="section-subtitle"><?= count($products) ?> sản phẩm sẵn sàng để bạn tham khảo</p>
        </div>
        <form class="filters" method="get">
            <label class="sr-only" for="q">Tìm sản phẩm</label>
            <input id="q" name="q" value="<?= e($keyword) ?>" placeholder="Tìm tên sản phẩm...">
            <?php if ($categories): ?>
                <select name="category" aria-label="Danh mục">
                    <option value="">Tất cả danh mục</option>
                    <?php foreach ($categories as $item): ?>
                        <option value="<?= e($item) ?>" <?= $category === $item ? 'selected' : '' ?>><?= e($item) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <button class="button" type="submit">Tìm kiếm</button>
        </form>
    </div>

    <?php if (!$products): ?>
        <div class="empty-state">Không tìm thấy sản phẩm phù hợp.</div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <article class="product-card">
                    <a class="product-card__image" href="<?= url('product.php?slug=' . urlencode($product['slug'])) ?>">
                        <?php if ($product['featured']): ?><span class="product-card__ribbon">Nổi bật</span><?php endif; ?>
                        <img loading="lazy" src="<?= e(imageUrl($product, $config)) ?>" alt="<?= e($product['name']) ?>">
                    </a>
                    <div class="product-card__body">
                        <?php if ($product['category']): ?><span class="badge"><?= e($product['category']) ?></span><?php endif; ?>
                        <h3><a href="<?= url('product.php?slug=' . urlencode($product['slug'])) ?>"><?= e($product['name']) ?></a></h3>
                        <p><?= e($product['short_description']) ?></p>
                        <div class="product-card__footer">
                            <strong><?= e($product['price']) ?></strong>
                            <a href="<?= url('product.php?slug=' . urlencode($product['slug'])) ?>">Xem chi tiết →</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
