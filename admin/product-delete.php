<?php
declare(strict_types=1);
require dirname(__DIR__) . '/src/bootstrap.php';
requireAdmin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('admin/index.php');
}
verifyCsrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
$stmt = $pdo->prepare('SELECT image_path FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();
if ($product) {
    $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
    $prefix = rtrim($config['upload_url'], '/') . '/';
    if (str_starts_with($product['image_path'], $prefix)) {
        $file = $config['base_path'] . '/' . $product['image_path'];
        if (is_file($file)) {
            unlink($file);
        }
    }
    flash('success', 'Đã xóa sản phẩm.');
}
redirect('admin/index.php');
