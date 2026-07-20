<?php
declare(strict_types=1);
require dirname(__DIR__) . '/src/bootstrap.php';
requireAdmin();
$search = trim((string) ($_GET['q'] ?? ''));
$stmt = $pdo->prepare('SELECT * FROM products WHERE name LIKE :search ORDER BY id DESC');
$stmt->execute(['search' => '%' . $search . '%']);
$products = $stmt->fetchAll();
$totalProducts = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$activeProducts = (int) $pdo->query('SELECT COUNT(*) FROM products WHERE is_active = 1')->fetchColumn();
$featuredProducts = (int) $pdo->query('SELECT COUNT(*) FROM products WHERE featured = 1')->fetchColumn();
$pageTitle = 'Quản trị sản phẩm';
require dirname(__DIR__) . '/partials/admin-header.php';
?>
<div class="admin-top">
    <div><p class="eyebrow">QUẢN TRỊ</p><h1>Sản phẩm</h1></div>
    <a class="button button--primary" href="<?= url('admin/product-form.php') ?>">+ Thêm sản phẩm</a>
</div>
<div class="admin-stats">
    <div class="stat-card"><span class="stat-card__icon">▦</span><div><small>Tổng sản phẩm</small><strong><?= $totalProducts ?></strong></div></div>
    <div class="stat-card"><span class="stat-card__icon stat-card__icon--green">✓</span><div><small>Đang hiển thị</small><strong><?= $activeProducts ?></strong></div></div>
    <div class="stat-card"><span class="stat-card__icon stat-card__icon--gold">★</span><div><small>Sản phẩm nổi bật</small><strong><?= $featuredProducts ?></strong></div></div>
</div>
<div class="admin-toolbar">
    <div><strong>Danh sách sản phẩm</strong><span><?= $search ? 'Kết quả cho “' . e($search) . '”' : 'Cập nhật và quản lý catalog' ?></span></div>
    <form class="filters" method="get"><label class="sr-only" for="admin-search">Tìm sản phẩm</label><input id="admin-search" name="q" value="<?= e($search) ?>" placeholder="Tìm sản phẩm..."><button class="button" type="submit">Tìm</button></form>
</div>
<?php if (!$products): ?><div class="empty-state"><h2>Chưa có sản phẩm phù hợp</h2><p>Thử từ khóa khác hoặc tạo sản phẩm mới.</p><a class="button button--primary" href="<?= url('admin/product-form.php') ?>">Thêm sản phẩm</a></div><?php else: ?>
<table class="admin-table">
    <thead><tr><th>Ảnh</th><th>Tên sản phẩm</th><th>Danh mục</th><th>Giá</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
    <tbody>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><img class="admin-thumb" src="<?= e(imageUrl($product, $config)) ?>" alt=""></td>
            <td><strong><?= e($product['name']) ?></strong><br><small><?= e($product['slug']) ?></small></td>
            <td><?= e($product['category'] ?: '—') ?></td>
            <td><?= e($product['price']) ?></td>
            <td><span class="status <?= !$product['is_active'] ? 'status--off' : '' ?>"><?= $product['is_active'] ? 'Đang hiện' : 'Đã ẩn' ?></span></td>
            <td><a class="button" href="<?= url('admin/product-form.php?id=' . $product['id']) ?>">Sửa</a>
                <form method="post" action="<?= url('admin/product-delete.php') ?>" style="display:inline" onsubmit="return confirm('Xóa sản phẩm này?')"><input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>"><input type="hidden" name="id" value="<?= (int) $product['id'] ?>"><button class="button" type="submit">Xóa</button></form></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<?php require dirname(__DIR__) . '/partials/admin-footer.php'; ?>
