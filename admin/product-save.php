<?php
declare(strict_types=1);
require dirname(__DIR__) . '/src/bootstrap.php';
requireAdmin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('admin/index.php');
}
verifyCsrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
$name = trim((string) ($_POST['name'] ?? ''));
if ($name === '') {
    flash('error', 'Tên sản phẩm là bắt buộc.');
    redirect('admin/product-form.php' . ($id ? '?id=' . $id : ''));
}
$existing = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $existing = $stmt->fetch();
    if (!$existing) {
        flash('error', 'Không tìm thấy sản phẩm.');
        redirect('admin/index.php');
    }
}
$imagePath = $existing['image_path'] ?? '';
$oldImagePath = $imagePath;
try {
    $newImage = uploadImage($_FILES['image'] ?? [], $config);
    if ($newImage !== '') {
        $imagePath = $newImage;
    }
} catch (Throwable $exception) {
    flash('error', $exception->getMessage());
    redirect('admin/product-form.php' . ($id ? '?id=' . $id : ''));
}
$data = [
    $name,
    uniqueSlug($pdo, $name, $id ?: null),
    trim((string) ($_POST['short_description'] ?? '')),
    trim((string) ($_POST['description'] ?? '')),
    trim((string) ($_POST['price'] ?? 'Liên hệ')) ?: 'Liên hệ',
    trim((string) ($_POST['category'] ?? '')),
    isset($_POST['featured']) ? 1 : 0,
    isset($_POST['is_active']) ? 1 : 0,
    $imagePath,
];
if ($id) {
    $data[] = $id;
    $stmt = $pdo->prepare('UPDATE products SET name=?, slug=?, short_description=?, description=?, price=?, category=?, featured=?, is_active=?, image_path=?, updated_at=CURRENT_TIMESTAMP WHERE id=?');
} else {
    $stmt = $pdo->prepare('INSERT INTO products (name, slug, short_description, description, price, category, featured, is_active, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
}
$stmt->execute($data);
if ($newImage !== '' && $oldImagePath !== '' && str_starts_with($oldImagePath, rtrim($config['upload_url'], '/') . '/')) {
    $oldImageFile = $config['base_path'] . '/' . $oldImagePath;
    if (is_file($oldImageFile)) {
        unlink($oldImageFile);
    }
}
flash('success', 'Đã lưu sản phẩm.');
redirect('admin/index.php');
