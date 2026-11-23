# AgriS MOVE trên WordPress – Hướng dẫn cài đặt và vận hành

Gồm 2 gói:

- **agris-move.zip** – theme (giao diện): trang chủ có hero đàn chim, Sự kiện đếm ngược, dải chủ đề quý, banner TOP 50, AgriS-thing, Bản tin MOVE; trang TOP 50 MOVERS; trang bài viết, danh sách, tìm kiếm.
- **agris-move-vote.zip** – plugin bình chọn: hồ sơ MOVER, danh sách nhân viên, đăng nhập bằng Họ tên + MSNV + SĐT, nút “love”, ghi IP, bảng kết quả, xuất Excel.

Đã chạy thử trên WordPress 6.8 và PHP 8.3. Yêu cầu tối thiểu: WordPress 6.2, PHP 7.4.

## 1. Cài đặt (khoảng 15 phút)

1. Có hosting WordPress (gói WordPress của Mắt Bão, PA Vietnam, Nhân Hòa… đều được) và cài WordPress tiếng Việt.
2. **Giao diện → Giao diện → Thêm mới → Tải giao diện lên** → chọn `agris-move.zip` → Kích hoạt.
3. **Plugin → Cài mới → Tải plugin lên** → chọn `agris-move-vote.zip` → Kích hoạt.
4. **Cài đặt → Đường dẫn tĩnh** → chọn “Tên bài viết” → Lưu.
5. **Cài đặt → Tổng quan** → Múi giờ: `Hồ Chí Minh`.
6. **Trang → Thêm mới**: tiêu đề “TOP 50 MOVERS”, ở khung bên phải chọn **Mẫu (Template): TOP 50 MOVERS** → Đăng.
7. **Cài đặt → Đọc**: “Trang chủ hiển thị” để mặc định “Các bài viết mới nhất” (theme tự dùng trang chủ riêng). Nếu muốn có trang danh sách Bản tin riêng, tạo trang “Bản tin” rồi chọn làm “Trang bài viết”.

## 2. Cập nhật nội dung hằng ngày

| Muốn làm | Vào đâu |
|---|---|
| Thêm sự kiện cho thẻ đếm ngược trên trang chủ | **Sự kiện → Thêm sự kiện**: tên, ngày giờ, “cả ngày” hay không, địa điểm, chữ trong dòng đếm ngược. Trang chủ tự hiện sự kiện gần nhất và tự chuyển khi qua ngày. |
| Đăng bài Bản tin MOVE | **Bài viết → Viết bài mới**, nhớ đặt **Ảnh đại diện** và **Chuyên mục** (hiện thành nhãn trên ảnh). 3 bài mới nhất lên trang chủ. |
| Đăng tập podcast AgriS-thing | **AgriS-thing → Thêm tập**: tiêu đề, mô tả, ảnh đại diện (ảnh bìa), link video (YouTube/Vimeo/Stream), thời lượng. 3 tập mới nhất lên trang chủ. |
| Đổi chủ đề quý đang phát sáng, banner TOP 50, đoạn chữ trên banner, link nút liên hệ | **Giao diện → Tuỳ biến → AgriS MOVE** |
| Sửa menu | **Giao diện → Menu**, gán vào vị trí “Menu chính”. Nếu chưa tạo menu, theme tự hiện các mục Về MOVE · Sự kiện · 50 Movers · AgriS-thing · Bản tin. |
| Thêm thông báo trên trang TOP 50 | Gõ vào nội dung của trang TOP 50 MOVERS; phần này hiện ngay dưới đoạn giới thiệu. |

Khi chưa có bài viết hoặc tập podcast nào, trang chủ hiện ảnh minh hoạ có sẵn để không bị trống.

## 3. Đơn ứng cử, đề cử, góp ý → Excel

Dùng plugin form miễn phí **Fluent Forms** (Plugin → Cài mới → tìm “Fluent Forms”):

1. Tạo 3 form: **Tự ứng cử**, **Đề cử đồng nghiệp**, **Góp ý**. Các trường gợi ý theo thể lệ: họ tên, MSNV, phòng ban, đơn vị, email; câu chuyện MOVE (bối cảnh, nhiệm vụ, hành động, kết quả); link video 60–90 giây (khuyên dùng link Google Drive/OneDrive/YouTube thay vì tải file video nặng lên web).
2. Chèn form vào trang bằng khối **Fluent Forms** hoặc shortcode của form.
3. Xem đơn tại **Fluent Forms → Entries**, bấm **Export** để tải Excel/CSV, rồi thẩm định thủ công như quy trình.

## 4. Bình chọn

1. **Bình chọn MOVE → Nhân viên**: tải lên file Excel (.xlsx) hoặc CSV gồm 4 cột **Họ và tên · MSNV · Số điện thoại · Đơn vị** (có file mẫu để tải). Có thể tải lại nhiều lần: hệ thống cập nhật theo MSNV.
2. Hồ sơ nào qua 2 vòng thẩm định thì tạo ở **MOVER → Thêm MOVER**: tiêu đề là họ tên, nội dung là câu chuyện MOVE, **Đoạn tóm tắt** hiện trên thẻ, **Ảnh chân dung** là ảnh đại diện, khung “Thông tin MOVER” để điền đơn vị, MSNV, link video.
3. **Bình chọn MOVE → Cài đặt**: thời gian mở/đóng cổng (mặc định 01/03/2027 00:00 → 30/04/2027 23:59), bật/tắt hiện số lượt love.
4. Danh sách MOVER và nút love tự hiện trên trang TOP 50 MOVERS (mục “Các MOVER”). Có thể đặt ở trang khác bằng shortcode `[amv_movers]`.
5. **Bình chọn MOVE → Kết quả**: bảng xếp hạng, cột Giải tự tính TOP 50 / AgriS’ Choice; nút **Xuất kết quả** và **Xuất chi tiết từng lượt love kèm IP** ra Excel.

Cách đăng nhập của người bình chọn: gõ họ tên (có gợi ý tên và đơn vị khi gõ), rồi tự gõ MSNV và số điện thoại. Cả 3 phải khớp danh sách. Số điện thoại gõ kiểu nào cũng được (0912…, +84 912…). Mỗi người love tối đa 1 lần cho mỗi MOVER, bấm lại để bỏ love.

**Chạy thử trước ngày mở:** tích “Mở thử” trong Cài đặt, bình chọn thử, rồi bỏ tích và bấm “Xoá toàn bộ lượt bình chọn”.

## 5. Lưu ý kỹ thuật cho người quản trị

- Số điện thoại không lưu dạng đọc được, chỉ lưu mã băm của 9 số cuối để đối chiếu. Gợi ý tên khi gõ chỉ trả về họ tên và đơn vị, không lộ MSNV hay SĐT.
- Mỗi lượt love lưu: thời gian, người bình chọn, MOVER, IP (REMOTE_ADDR) và X-Forwarded-For (nếu website đứng sau Cloudflare/proxy, IP thật nằm ở cột này).
- Đăng nhập sai quá 10 lần trong 15 phút từ một IP sẽ bị tạm chặn.
- Nếu dùng plugin cache trang (WP Rocket, LiteSpeed Cache…), loại trừ trang TOP 50 MOVERS và các trang MOVER khỏi cache để nút love luôn đúng trạng thái.
- Khi gỡ plugin, dữ liệu bình chọn và danh sách nhân viên vẫn được giữ trong database.
- Cần bật HTTPS cho website (hosting thường có SSL miễn phí).
