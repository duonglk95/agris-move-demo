<?php
/**
 * Template Name: TOP 50 – Hướng dẫn và thể lệ
 * Child page of the TOP 50 MOVERS page: step-by-step guide, rules accordion and FAQ.
 */
get_header();
while ( have_posts() ) : the_post();
	$steps = array(
		array( 'Chọn cách tham gia', '01/12/2026 – 28/02/2027', '<p>Tất cả CBNV đang làm việc tại AgriS (HO, AgriC, ProC, ComC) đều tham gia được, theo một trong ba cách:</p><ul><li><b>Tự ứng cử:</b> tự nộp hồ sơ với câu chuyện MOVE của chính bạn.</li><li><b>Đề cử đồng nghiệp:</b> điền form đề cử kèm lý do ngắn; Ban Tổ chức mời người được đề cử nộp hồ sơ.</li><li><b>Đơn vị đề cử:</b> lãnh đạo, quản lý hoặc HR gửi danh sách nhân sự tiêu biểu.</li></ul>' ),
		array( 'Chuẩn bị hồ sơ', 'Mỗi người một hồ sơ', '<ul><li><b>Thông tin:</b> họ tên, mã nhân viên, phòng ban, đơn vị, email.</li><li><b>Câu chuyện MOVE</b> theo bốn ý: Bối cảnh, Nhiệm vụ, Hành động, Kết quả.</li><li><b>Video 60 – 90 giây</b> (bắt buộc): giới thiệu bản thân, điều bạn muốn thay đổi, hoặc kể chính câu chuyện MOVE.</li></ul>' ),
		array( 'Nộp hồ sơ trên website', 'Hạn chót 28/02/2027', '<p>Dù tự ứng cử hay được đề cử, mỗi ứng viên tự nộp hồ sơ của mình. Làm việc tại nhà máy, vùng nguyên liệu? HR và truyền thông nội bộ đơn vị sẽ hỗ trợ bạn nộp hồ sơ và quay video.</p>' ),
		array( 'Thẩm định hai vòng', 'Trước khi đăng lên website', '<ul><li><b>Vòng 1 · HR đơn vị:</b> kiểm tra điều kiện ứng viên.</li><li><b>Vòng 2 · Ban Tổ chức:</b> câu chuyện nêu rõ hành động, kết quả và thể hiện ít nhất 1 trong 4 tinh thần MOVE.</li></ul><p>Hồ sơ cần bổ sung được gửi lại kèm gợi ý; sau 10 ngày không phản hồi, hồ sơ không hợp lệ.</p>' ),
		array( 'Bình chọn', '01/03 – 30/04/2027', '<p>Đăng nhập bằng họ tên, MSNV và số điện thoại, không cần mật khẩu. Bạn thả “love” cho nhiều MOVER, mỗi MOVER tối đa một lượt. Chia sẻ câu chuyện bạn yêu thích để rủ đồng nghiệp cùng bình chọn.</p>' ),
		array( 'Công bố và vinh danh', '16/05/2027', '<p>Công bố TOP 50 AgriS MOVERS và AgriS’ Choice sau giai đoạn tiếp nhận khiếu nại (01 – 10/05/2027). Lễ vinh danh tại sự kiện chính thức, thông báo sau.</p>' ),
	);
	$top50 = agm_top50_url();
	?>
<main id="top" class="tk g50">
  <section class="tk-hero">
    <div class="in tk-hero-txt g50-hero">
      <p class="tk-kicker">TOP 50 MOVERS · Mùa 1</p>
      <h1 class="gt"><?php the_title(); ?></h1>
      <div class="tk-intro"><?php the_content(); ?></div>
    </div>
  </section>

  <section class="band g50-steps-sec">
    <div class="in">
      <ol class="g50-steps">
        <?php foreach ( $steps as $i => $st ) : ?>
        <li class="pc-step g50-step">
          <h3><span><?php echo (int) $i + 1; ?></span><?php echo esc_html( $st[0] ); ?></h3>
          <p class="g50-when"><?php echo esc_html( $st[1] ); ?></p>
          <div class="g50-body"><?php echo wp_kses_post( $st[2] ); ?></div>
        </li>
        <?php endforeach; ?>
      </ol>
      <div class="pc-dl g50-cta"><h3><span>✓</span>Sẵn sàng? Đọc câu chuyện MOVE của đồng nghiệp</h3><a class="btn" href="<?php echo esc_url( $top50 . '#cac-mover' ); ?>">Xem các MOVER</a></div>
    </div>
  </section>

  <div class="seam" data-next="#e9f6fb" data-h="90" data-tones="#d3eadb,#9bd8b1d0" data-line="lime"></div>
  <section class="band ct-rules" id="the-le">
    <div class="in ct-faq">
      <div><h2 class="gt">Thể lệ<br>chi tiết</h2></div>
      <div class="ct-acc">
        <details open><summary>Điều kiện ứng viên</summary><div><ul><li>Đang làm việc tại AgriS.</li><li>Không có quyết định kỷ luật hay vi phạm quy tắc ứng xử trong niên độ.</li><li>Không có kết quả KPI Không đạt ở cả hai kỳ gần nhất.</li></ul></div></details>
        <details><summary>Tiêu chí câu chuyện MOVE</summary><div><p>Câu chuyện nêu rõ hành động và kết quả, thể hiện ít nhất một tinh thần:</p><ul><li><b>Action:</b> chủ động hành động, không đợi được giao hay nhắc.</li><li><b>Innovation:</b> thử cách làm mới hoặc khác trước, dù chỉ là cải tiến nhỏ.</li><li><b>Impact:</b> tạo thay đổi cụ thể cho đội nhóm, khách hàng hoặc đơn vị.</li><li><b>As One:</b> phối hợp, hỗ trợ, chia sẻ và lan toả đến đồng nghiệp.</li></ul></div></details>
        <details><summary>Quy định bình chọn</summary><div><ul><li>Chỉ dùng tài khoản của chính mình; mọi lượt bình chọn có giá trị như nhau.</li><li>Nghiêm cấm dùng tài khoản người khác, công cụ bình chọn tự động hoặc trao đổi lượt bình chọn.</li><li>Vi phạm có thể bị huỷ lượt bình chọn, loại khỏi chương trình hoặc thu hồi danh hiệu.</li></ul></div></details>
        <details><summary>Giải thưởng</summary><div><ul><li><b>TOP 50 AgriS MOVERS:</b> 50 MOVER có lượt bình chọn cao nhất toàn hệ thống.</li><li><b>AgriS’ Choice:</b> MOVER ngoài TOP 50 đạt từ 100 lượt bình chọn.</li></ul><p><a href="<?php echo esc_url( $top50 . '#giai-thuong' ); ?>">Xem chi tiết giải thưởng</a></p></div></details>
        <details><summary>Xác định kết quả và khiếu nại</summary><div><p>Theo tổng lượt bình chọn hợp lệ đến hết 30/04/2027. Nếu nhiều ứng viên bằng lượt ở vị trí thứ 50: từ 3 người trở xuống đều vào TOP 50; từ 4 người trở lên, ưu tiên người nộp hồ sơ sớm hơn. Khiếu nại gửi Ban Tổ chức từ 01 – 10/05/2027; quyết định của Hội đồng xác nhận kết quả là quyết định cuối cùng.</p></div></details>
        <details><summary>Sử dụng AI và quyền sử dụng nội dung</summary><div><p>Bạn được dùng AI hỗ trợ viết và dựng video, nhưng thông tin phải đúng sự thật. Khi nộp hồ sơ, bạn đồng ý để AgriS sử dụng câu chuyện, hình ảnh và video cho mục đích truyền thông nội bộ.</p></div></details>
      </div>
    </div>
  </section>

  <section class="band" id="hoi-dap">
    <div class="in ct-faq">
      <div><h2 class="gt">Câu hỏi<br>thường gặp</h2><img src="<?php echo esc_url( agm_img( 'abi-flag.webp' ) ); ?>" alt="" aria-hidden="true"></div>
      <div class="ct-acc">
        <details open><summary>Tôi có thể tự ứng cử không?</summary><div><p>Có. Chương trình khuyến khích bạn tự tin kể câu chuyện MOVE của chính mình.</p></div></details>
        <details><summary>Tôi được đồng nghiệp hoặc đơn vị đề cử thì cần làm gì?</summary><div><p>Ban Tổ chức sẽ gửi lời mời và hướng dẫn. Bạn tự hoàn thiện và nộp hồ sơ trên website trước ngày 28/02/2027.</p></div></details>
        <details><summary>Tôi làm việc tại nhà máy, vùng nguyên liệu thì tham gia thế nào?</summary><div><p>HR và bộ phận truyền thông nội bộ tại đơn vị sẽ hướng dẫn bạn nộp hồ sơ, quay video và bình chọn.</p></div></details>
        <details><summary>Tôi có cần đăng ký tài khoản để bình chọn không?</summary><div><p>Không. Bạn chỉ cần nhập họ tên, MSNV và số điện thoại đã đăng ký với HR.</p></div></details>
      </div>
    </div>
  </section>
</main>
<?php endwhile; get_footer();
