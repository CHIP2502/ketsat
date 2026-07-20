# Nhật ký thay đổi

## 2026-07-20 — Chuyển website tĩnh sang PHP

- Thay entrypoint HTML bằng ứng dụng PHP 8 thuần.
- Dùng SQLite, tự tạo schema và nhập 16 sản phẩm mẫu ở lần chạy đầu.
- Thêm trang danh sách, tìm kiếm, lọc danh mục và chi tiết sản phẩm.
- Thêm trang quản trị có đăng nhập, CRUD, ẩn/hiện, sản phẩm nổi bật và upload ảnh.
- Thêm password hash, session bảo mật, CSRF, prepared statement và kiểm tra MIME/kích thước ảnh.
- Thêm CSS responsive mới, router cho PHP built-in server, `.htaccess` bảo vệ dữ liệu nội bộ và README hướng dẫn chạy.
- Xóa `index.html` và `getDetails.html` legacy khỏi luồng chạy; giữ kho ảnh cũ để tái sử dụng.

Lưu ý: môi trường thực hiện chưa có PHP CLI nên chưa chạy được test runtime; đã kiểm tra tĩnh, đường dẫn, dữ liệu ảnh và `git diff --check`.

## 2026-07-21 — Cập nhật UI/UX

- Làm mới storefront với header sticky, CTA gọi ngay, trust strip, trạng thái sản phẩm nổi bật, hierarchy tìm kiếm và responsive mobile.
- Làm mới admin với dashboard stats, toolbar tìm kiếm, empty state, table hover/status và form chia thành 3 bước.
- Giữ nguyên logic nghiệp vụ và database; chỉ bổ sung các class/template phục vụ trải nghiệm.

## 2026-07-21 — Thêm đổi mật khẩu admin

- Thêm `admin/change-password.php` với xác thực mật khẩu hiện tại, kiểm tra độ dài, xác nhận mật khẩu, CSRF và password hash.
- Thêm liên kết đổi mật khẩu vào sidebar admin và cập nhật README.

## 2026-07-21 — Dọn tài nguyên legacy

- Chuyển 14 ảnh seed đang sử dụng sang `assets/images/products/` và placeholder sang `assets/images/`.
- Chuyển thư mục upload runtime từ `data/uploads/` sang `uploads/`.
- Xóa toàn bộ `themes/` và `data/` sau khi kiểm tra không còn tham chiếu trong ứng dụng.
