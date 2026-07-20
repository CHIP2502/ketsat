<?php
declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = ''): string
{
    $script = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    if (str_ends_with($script, '/admin')) {
        $script = dirname($script);
    }
    $root = rtrim($script === '/' || $script === '.' ? '' : $script, '/');
    return $root . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url($path);
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function slugify(string $value): string
{
    $value = trim($value);
    $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value;
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    return trim($value, '-') ?: 'san-pham';
}

function uniqueSlug(PDO $pdo, string $name, ?int $ignoreId = null): string
{
    $base = slugify($name);
    $slug = $base;
    $suffix = 2;
    while (true) {
        $sql = 'SELECT id FROM products WHERE slug = :slug' . ($ignoreId ? ' AND id != :id' : '');
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':slug', $slug);
        if ($ignoreId) {
            $stmt->bindValue(':id', $ignoreId, PDO::PARAM_INT);
        }
        $stmt->execute();
        if (!$stmt->fetch()) {
            return $slug;
        }
        $slug = $base . '-' . $suffix++;
    }
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function verifyCsrf(): void
{
    $token = $_POST['csrf'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
        http_response_code(419);
        exit('Phiên biểu mẫu đã hết hạn. Vui lòng thử lại.');
    }
}

function isAdmin(): bool
{
    return !empty($_SESSION['admin_id']);
}

function requireAdmin(): void
{
    if (!isAdmin()) {
        redirect('admin/login.php');
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function consumeFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function imageUrl(array $product, array $config): string
{
    $path = $product['image_path'] ?: 'assets/images/product-placeholder.svg';
    return asset($path);
}

function uploadImage(array $file, array $config): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return '';
    }
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 8 * 1024 * 1024) {
        throw new RuntimeException('Ảnh không hợp lệ hoặc vượt quá 8 MB.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($extensions[$mime]) || @getimagesize($file['tmp_name']) === false) {
        throw new RuntimeException('Chỉ chấp nhận ảnh JPG, PNG hoặc WebP.');
    }
    if (!is_dir($config['upload_dir'])) {
        mkdir($config['upload_dir'], 0755, true);
    }
    $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
    $destination = $config['upload_dir'] . DIRECTORY_SEPARATOR . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Không thể lưu ảnh tải lên.');
    }
    return $config['upload_url'] . '/' . $filename;
}

function seedProducts(PDO $pdo, array $config): void
{
    if ((int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn() > 0) {
        return;
    }
    $products = [
        ['Két sắt Việt Tiệp siêu cường VE5607 mã số điện tử - Model 2024', 'assets/images/products/small_12_2023_675_ket-sat-viet-tiep-sieu-cuong-ve5507.jpg'],
        ['Két sắt Việt Tiệp siêu cường VFE5007 vân tay mã số báo động - Model 2023', 'assets/images/products/small_09_2023_656_ket-sat-viet-tiep-sieu-cuong-vfe5007-van-tay-ma-so-bao-dong-model-2023.jpg'],
        ['Két sắt Việt Tiệp siêu cường VE3307-B màu trắng điện tử báo động - Model 2023', 'assets/images/products/small_08_2023_655_ket-sat-viet-tiep-sieu-cuong-ve3307-b-mau-trang-dien-tu-bao-dong-model-2023.jpg'],
        ['Két sắt thông minh Aifeibao HK/MD55AS vân tay điện tử App điện thoại', 'assets/images/products/small_11_2021_549_ket-sat-thong-minh-aifeibao-hk-md55as-van-tay-dien-tu-app-dien-thoai-.jpg'],
        ['Két sắt Aifeibao HK-M/D-60-BL vân tay điện tử', 'assets/images/products/small_12_2020_443_ket-sat-van-tay-thong-minh-aifeibao-hk-m-d-60-bl.jpg'],
        ['Két sắt Goldbank cánh đúc GDC56', 'assets/images/products/small_10_2018_324_ket-sat-goldbank-canh-duc-gdc56.jpg'],
        ['Két sắt Goldbank khóa điện tử có báo động GVE125', 'assets/images/products/small_04_2020_206_ket-sat-goldbank-khoa-dien-tu-co-bao-dong-gve125.jpg'],
        ['Két sắt Hòa Phát The One xuất khẩu có báo động KS110-Royal', 'assets/images/products/small_05_2023_188_ket-sat-hoa-phat-xuat-khau-co-bao-dong-ks110-royal.jpg'],
        ['Két sắt siêu cường Việt Tiệp VFE5607 vân tay - Model 2024', 'assets/images/products/small_12_2023_676_ket-sat-sieu-cuong-viet-tiep-vfe5607-van-tay-model-moi-nhat.jpg'],
        ['Két sắt Việt Tiệp vân tay mã số VFE5007 màu ghi xanh - Model 2023', 'assets/images/products/small_09_2023_659_ket-sat-viet-tiep-van-tay-ma-so-vfe5007-mau-ghi-xanh-model-2023.jpg'],
        ['Két sắt Việt Tiệp siêu cường VE3307-B màu đen điện tử báo động', 'assets/images/products/small_08_2023_654_ket-sat-viet-tiep-sieu-cuong-ve3307-b-mau-den-dien-tu-bao-dong-model-2023.jpg'],
        ['Két sắt Việt Tiệp VBE09 điện tử - Model 2022', 'assets/images/products/small_12_2022_605_ket-sat-viet-tiep-vbe09-dien-tu-model-2022.jpg'],
        ['Két sắt Việt Tiệp VBC09 khóa cơ - Model 2022', 'assets/images/products/small_12_2022_604_ket-sat-viet-tiep-vbc09-khoa-co-model-2022.jpg'],
        ['Két sắt siêu cường Việt Tiệp VE5908 điện tử - Model 2023', 'assets/images/products/small_01_2023_603_ket-sat-sieu-cuong-viet-tiep-ve5908-dien-tu-model-2022.jpg'],
        ['Két sắt thả tiền ngăn kéo Việt Tiệp', 'assets/images/products/small_12_2023_675_ket-sat-viet-tiep-sieu-cuong-ve5507.jpg'],
        ['Két sắt mini Việt Tiệp khóa điện tử', 'assets/images/products/small_08_2023_654_ket-sat-viet-tiep-sieu-cuong-ve3307-b-mau-den-dien-tu-bao-dong-model-2023.jpg'],
    ];
    $stmt = $pdo->prepare('INSERT INTO products (name, slug, image_path, short_description, description) VALUES (?, ?, ?, ?, ?)');
    foreach ($products as $product) {
        $stmt->execute([$product[0], uniqueSlug($pdo, $product[0]), $product[1], 'Sản phẩm chính hãng, tư vấn và giao hàng toàn quốc.', 'Liên hệ để được tư vấn kích thước, tính năng và chính sách bảo hành phù hợp.']);
    }
}
