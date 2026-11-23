# Quy trình chỉnh sửa AgriS MOVE

Nguồn chính là repo này. `wordpress/agris-move` (theme) và `wordpress/agris-move-vote` (plugin bình chọn).
Bản demo HTML tĩnh: `index.html`, `top50-movers.html`.

## Mỗi phiên chỉnh mới
1. Clone repo, chạy `bash dev/bootstrap.sh` để dựng WordPress local (SQLite, admin / Admin#12345, dữ liệu mẫu từ `dev/seed.php`).
2. `php -S localhost:8080 dev/router.php`, chụp màn hình bằng headless browser để duyệt.
3. Sửa trong `wordpress/…`, chạy lại phần copy của bootstrap rồi xem lại.
4. Cuối đợt: commit + push. Chưa upload WordPress thật.

## Khi chốt bản cuối
Zip `wordpress/agris-move` và `wordpress/agris-move-vote`, tải lên https://agrismove-demo.instawp.site (Giao diện/Plugin → Tải lên → Thay thế). Nội dung trong database không bị ảnh hưởng.

## Bản đồ theme
- Hero animation: `assets/js/hero.js` (timeline T, START=4.2; chữ dưới đội hình, TY=740)
- Trang chủ: `front-page.php`, `assets/css/site.css`, `assets/js/site.js`
- TOP 50: `page-top50.php`, `assets/css/top50.css` (scope `.t50`), `assets/js/top50.js`
- Post type sự kiện/podcast: `inc/content-types.php`; Customizer: `inc/customizer.php`
- Plugin: tables `wp_amv_employees`, `wp_amv_votes`; admin `includes/admin.php`; front `includes/front.php`

## Lưu ý
- SĐT chỉ lưu dạng hash; MSNV và SĐT phải do người dùng tự gõ.
- Không dùng Railway.

## Mini-site sự kiện (Vào vụ)
- Trang cha dùng mẫu "Mini-site sự kiện" (`page-event-hub.php`); trang con = hoạt động, hiện trong menu riêng theo thứ tự trang.
- Mẫu hoạt động: `page-thanks.php` (Lời tiếp sức, chờ duyệt), `page-postcard.php`, `page-avatar.php`, `page-contest.php` (ảnh đăng ngay, admin hậu kiểm; love dùng plugin bình chọn qua filter `amv_vote_types`).
- Canvas khung ảnh/bưu thiếp/avatar: `assets/js/studio.js`; khung PNG thật tải ở Tùy biến → Khung ảnh sự kiện.
- Pit Crew: `page-pitcrew.php` + `inc/pitcrew.php` (tour và hạn ở Tùy biến → Pit Crew; xác thực qua danh sách nhân viên của plugin).
- Seed local: `php dev/seed-ev.php`, `php dev/seed-mini.php`, `php dev/seed-pit.php`.
