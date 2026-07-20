# Két sắt Việt Tiệp

Website PHP thuần cho catalog sản phẩm két sắt, sử dụng SQLite và không cần framework.

## Chạy local

Yêu cầu PHP 8.0 trở lên với các extension `pdo_sqlite`, `fileinfo` và `gd`/`exif` tùy môi trường.

```powershell
php -S localhost:8000 router.php
```

Mở `http://localhost:8000`.

Database SQLite được tạo tự động tại `database/app.sqlite` ở lần chạy đầu tiên. Ứng dụng cũng tự nhập 16 sản phẩm mẫu từ website cũ nếu database chưa có sản phẩm.

## Quản trị

Mở `http://localhost:8000/admin/login.php`.

- Tên đăng nhập mặc định: `admin`
- Mật khẩu mặc định: `ChangeMe123!`

Đổi thông tin mặc định bằng biến môi trường trước lần chạy đầu tiên:

```powershell
$env:ADMIN_USERNAME = 'admin-moi'
$env:ADMIN_PASSWORD = 'mat-khau-manh'
php -S localhost:8000
```

Trang quản trị hỗ trợ tạo, sửa, xóa, ẩn/hiện, đánh dấu nổi bật và upload ảnh đại diện sản phẩm. Ảnh upload mới nằm trong `uploads/`; ảnh sản phẩm mặc định nằm trong `assets/images/products/`.

Sau khi đăng nhập, vào mục `Đổi mật khẩu` trong sidebar để đổi mật khẩu. Hệ thống yêu cầu mật khẩu hiện tại, mật khẩu mới tối thiểu 8 ký tự và xác nhận mật khẩu mới.

## Cấu trúc chính

- `index.php`: danh sách, tìm kiếm và lọc sản phẩm.
- `product.php`: trang chi tiết sản phẩm.
- `admin/`: đăng nhập và CRUD sản phẩm.
- `src/`: bootstrap, database, helper và upload validation.
- `partials/`: layout public/admin.
- `config/config.php`: cấu hình ứng dụng.

Không commit `database/app.sqlite` hoặc ảnh upload lên repository production nếu dữ liệu đó chứa thông tin riêng tư.
