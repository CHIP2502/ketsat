<?php
declare(strict_types=1);
require dirname(__DIR__) . '/src/bootstrap.php';
requireAdmin();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$product = ['id' => 0, 'name' => '', 'short_description' => '', 'description' => '', 'price' => 'Liên hệ', 'category' => '', 'featured' => 0, 'is_active' => 1, 'image_path' => ''];
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch() ?: $product;
}
$pageTitle = $id ? 'Sửa sản phẩm' : 'Thêm sản phẩm';
require dirname(__DIR__) . '/partials/admin-header.php';
?>
<div class="admin-top"><div><p class="eyebrow">QUẢN TRỊ</p><h1><?= $id ? 'Sửa sản phẩm' : 'Thêm sản phẩm' ?></h1></div><a class="button" href="<?= url('admin/index.php') ?>">← Danh sách</a></div>
<form class="admin-form" method="post" action="<?= url('admin/product-save.php') ?>" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>"><input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
    <div class="form-section"><div class="form-section__heading"><span class="form-step">01</span><div><h2>Thông tin cơ bản</h2><p>Những thông tin khách hàng nhìn thấy đầu tiên.</p></div></div><div class="form-grid">
        <div class="form-field form-field--full"><label for="name">Tên sản phẩm *</label><input id="name" name="name" required value="<?= e($product['name']) ?>"></div>
        <div class="form-field"><label for="category">Danh mục</label><input id="category" name="category" value="<?= e($product['category']) ?>" placeholder="Ví dụ: Két điện tử"></div>
        <div class="form-field"><label for="price">Giá hiển thị</label><input id="price" name="price" value="<?= e($product['price']) ?>"></div>
        <div class="form-field form-field--full"><label for="short_description">Mô tả ngắn</label><input id="short_description" name="short_description" value="<?= e($product['short_description']) ?>"></div>
        <div class="form-field form-field--full"><label for="description">Thông tin sản phẩm</label><textarea id="description" name="description" placeholder="Kích thước, tính năng, bảo hành..."><?= e($product['description']) ?></textarea></div>
    </div></div>
    <div class="form-section"><div class="form-section__heading"><span class="form-step">02</span><div><h2>Hình ảnh</h2><p>Ảnh rõ nét giúp sản phẩm dễ được chọn hơn.</p></div></div><div class="form-grid">
        <div class="form-field form-field--full"><label for="image">Ảnh đại diện (JPG, PNG, WebP tối đa 8 MB)</label><input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp"></div>
        <?php if ($product['image_path']): ?><div class="form-field form-field--full"><img class="admin-thumb" style="width:140px;height:110px" src="<?= e(imageUrl($product, $config)) ?>" alt="Ảnh hiện tại"></div><?php endif; ?>
    </div></div>
    <div class="form-section"><div class="form-section__heading"><span class="form-step">03</span><div><h2>Trạng thái hiển thị</h2><p>Kiểm soát sản phẩm xuất hiện trên website như thế nào.</p></div></div><div class="form-grid">
        <label class="check-field"><input type="checkbox" name="featured" value="1" <?= $product['featured'] ? 'checked' : '' ?>> Sản phẩm nổi bật</label>
        <label class="check-field"><input type="checkbox" name="is_active" value="1" <?= $product['is_active'] ? 'checked' : '' ?>> Hiển thị trên website</label>
    </div></div>
    <div class="form-actions"><button class="button button--primary" type="submit">Lưu sản phẩm</button><a class="button" href="<?= url('admin/index.php') ?>">Hủy</a></div>
</form>
<?php require dirname(__DIR__) . '/partials/admin-footer.php'; ?>
