<?php
declare(strict_types=1);
require dirname(__DIR__) . '/src/bootstrap.php';
requireAdmin();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $currentPassword = (string) ($_POST['current_password'] ?? '');
    $newPassword = (string) ($_POST['new_password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    $stmt = $pdo->prepare('SELECT password_hash FROM admins WHERE id = ? LIMIT 1');
    $stmt->execute([(int) $_SESSION['admin_id']]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($currentPassword, $admin['password_hash'])) {
        $error = 'Mật khẩu hiện tại không đúng.';
    } elseif (strlen($newPassword) < 8) {
        $error = 'Mật khẩu mới phải có ít nhất 8 ký tự.';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'Mật khẩu xác nhận không khớp.';
    } elseif ($currentPassword === $newPassword) {
        $error = 'Mật khẩu mới phải khác mật khẩu hiện tại.';
    } else {
        $stmt = $pdo->prepare('UPDATE admins SET password_hash = ? WHERE id = ?');
        $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), (int) $_SESSION['admin_id']]);
        flash('success', 'Đổi mật khẩu thành công.');
        redirect('admin/index.php');
    }
}

$pageTitle = 'Đổi mật khẩu';
require dirname(__DIR__) . '/partials/admin-header.php';
?>
<div class="admin-top">
    <div><p class="eyebrow">TÀI KHOẢN</p><h1>Đổi mật khẩu</h1></div>
    <a class="button" href="<?= url('admin/index.php') ?>">← Về sản phẩm</a>
</div>

<form class="admin-form password-form" method="post" autocomplete="off">
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
    <div class="form-section">
        <div class="form-section__heading">
            <span class="form-step">🔒</span>
            <div><h2>Bảo mật tài khoản</h2><p>Dùng mật khẩu mới mạnh và không dùng lại ở nơi khác.</p></div>
        </div>
        <?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
        <div class="form-field"><label for="current_password">Mật khẩu hiện tại</label><input id="current_password" type="password" name="current_password" required autocomplete="current-password"></div>
        <div class="form-field"><label for="new_password">Mật khẩu mới</label><input id="new_password" type="password" name="new_password" minlength="8" required autocomplete="new-password"><small class="field-hint">Tối thiểu 8 ký tự.</small></div>
        <div class="form-field"><label for="confirm_password">Nhập lại mật khẩu mới</label><input id="confirm_password" type="password" name="confirm_password" minlength="8" required autocomplete="new-password"></div>
    </div>
    <div class="form-actions"><button class="button button--primary" type="submit">Lưu mật khẩu mới</button><a class="button" href="<?= url('admin/index.php') ?>">Hủy</a></div>
</form>
<?php require dirname(__DIR__) . '/partials/admin-footer.php'; ?>
