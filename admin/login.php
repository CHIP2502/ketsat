<?php
declare(strict_types=1);
require dirname(__DIR__) . '/src/bootstrap.php';
if (isAdmin()) {
    redirect('admin/index.php');
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = :username LIMIT 1');
    $stmt->execute(['username' => $username]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        redirect('admin/index.php');
    }
    $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
}
$pageTitle = 'Đăng nhập quản trị';
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= asset('assets/css/app.css') ?>">
</head>
<body class="admin-body">
<section class="login-card">
    <a class="brand" href="<?= url('index.php') ?>">KÉT SẮT <span>VIỆT TIỆP</span></a>
    <p class="eyebrow">KHU VỰC QUẢN TRỊ</p>
    <h1>Đăng nhập</h1>
    <?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
        <div class="form-field"><label for="username">Tên đăng nhập</label><input id="username" name="username" required autocomplete="username"></div>
        <div class="form-field"><label for="password">Mật khẩu</label><input id="password" type="password" name="password" required autocomplete="current-password"></div>
        <button class="button button--primary button--block" type="submit">Đăng nhập</button>
    </form>
</section>
</body>
</html>
