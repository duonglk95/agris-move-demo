<?php
/**
 * Template Name: TOP 50 MOVERS
 * The award page. Text in the page editor shows as an announcement under the intro;
 * MOVER profiles and voting come from the "AgriS MOVE – Bình chọn" plugin.
 */
get_header();
while ( have_posts() ) :
	the_post();
	?>
<main id="top" class="t50">
  <figure class="banner" style="margin:0">
    <img src="<?php echo esc_url( agm_top50_banner() ); ?>" alt="AgriS TOP 50 MOVERS, linh vật ABI đứng trên bục vinh danh" width="1600" height="900">
    <p class="lede">Giải thưởng văn hoá thường niên của AgriS, do chính AgriS-ers bình chọn.</p>
  </figure>

  <div class="in">
    <section class="intro" aria-labelledby="t-intro">
      <div>
        <span class="kicker">TOP 50 MOVERS Awards · Mùa 1 · Niên độ 2026 – 2027</span>
        <h1 id="t-intro">Dám bước ra, tự tin thể hiện giá trị</h1>
        <p>Mỗi ngày, ở văn phòng, nhà máy hay vùng nguyên liệu, có rất nhiều AgriS-er đang lặng lẽ MOVE: chủ động nhận việc, thử một cách làm mới, hỗ trợ đồng nghiệp. TOP 50 MOVERS là giải thưởng văn hoá thường niên của AgriS, do chính AgriS-ers bình chọn, để những câu chuyện ấy được kể ra và được ghi nhận.</p>
        <div class="actions">
          <a class="btn main" id="ctaMain" href="#tham-gia">Xem cách tham gia <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 8h11M9 4l4 4-4 4"></path></svg></a>
          <a class="btn ghost" href="#hoi-dap">Hỏi nhanh – đáp gọn</a>
        </div>
      </div>
      <aside class="status" aria-live="polite">
        <p class="phase"><span id="phaseLabel">Mở nhận hồ sơ sau</span><b id="phaseTitle">Nộp hồ sơ từ 01/12/2026</b></p>
        <div class="tiles" role="timer" id="phaseTimer">
          <div><b id="cD">--</b><span>Ngày</span></div>
          <div><b id="cH">--</b><span>Giờ</span></div>
          <div><b id="cM">--</b><span>Phút</span></div>
          <div><b id="cS">--</b><span>Giây</span></div>
        </div>
      </aside>
    </section>
  </div>

  <?php if ( trim( get_the_content() ) ) : ?>
  <section class="sec" id="thong-bao">
    <div class="in entry"><?php the_content(); ?></div>
  </section>
  <?php endif; ?>

  <section class="sec mint" id="lich" aria-labelledby="t-lich">
    <div class="in">
      <div class="sec-head"><h2 id="t-lich">Thời gian biểu</h2></div>
      <ol class="timeline">
        <li data-from="2026-11-01" data-to="2026-11-30"><b>Ra mắt website AgriS MOVE</b><time>Tháng 11/2026</time></li>
        <li data-from="2026-12-01" data-to="2027-02-28"><b>Nộp hồ sơ, đề cử</b><time>01/12/2026 – 28/02/2027</time></li>
        <li data-from="2027-03-01" data-to="2027-04-30"><b>Bình chọn</b><time>01/03 – 30/04/2027</time></li>
        <li data-from="2027-05-01" data-to="2027-05-10"><b>Tiếp nhận khiếu nại</b><time>01 – 10/05/2027</time></li>
        <li data-from="2027-05-16" data-to="2027-05-16"><b>Công bố TOP 50 và AgriS’ Choice</b><time>16/05/2027</time></li>
        <li><b>Vinh danh</b><time>Tại sự kiện chính thức (thông báo sau)</time></li>
      </ol>
    </div>
  </section>

  <section class="sec" id="tham-gia" aria-labelledby="t-thamgia">
    <div class="in">
      <div class="sec-head">
        <h2 id="t-thamgia">Ba cách tham gia</h2>
        <p>Tất cả CBNV đang làm việc tại AgriS (HO, AgriC, ProC, ComC) đều có thể tham gia. Dù được đề cử hay tự ứng cử, mỗi ứng viên đều tự nộp hồ sơ của mình trên website từ 01/12/2026 đến 28/02/2027, mỗi người một hồ sơ.</p>
      </div>
      <div class="cards3">
        <article class="card">
          <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"></circle><path d="M4 21c1.5-4 4.5-6 8-6s6.5 2 8 6"></path></svg></span>
          <h3>Tự ứng cử</h3>
          <p>Tự nộp hồ sơ trên website với câu chuyện MOVE của chính bạn: một lần dám thử, dám đề xuất, dám làm khác đi.</p>
        </article>
        <article class="card">
          <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"></circle><path d="M2.5 20c1.2-3.4 3.6-5 6.5-5s5.3 1.6 6.5 5"></path><path d="M17 8h5M19.5 5.5v5"></path></svg></span>
          <h3>Đề cử đồng nghiệp</h3>
          <p>Điền form đề cử kèm lý do ngắn. Ban Tổ chức sẽ gửi lời mời để người được đề cử tự nộp hồ sơ.</p>
        </article>
        <article class="card">
          <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21V9l9-6 9 6v12"></path><path d="M9 21v-6h6v6"></path></svg></span>
          <h3>Đơn vị đề cử</h3>
          <p>Lãnh đạo, quản lý hoặc HR đơn vị gửi danh sách nhân sự tiêu biểu. Ban Tổ chức sẽ mời họ tham gia.</p>
        </article>
      </div>
      <p class="note">Làm việc tại nhà máy hoặc vùng nguyên liệu? HR và bộ phận truyền thông nội bộ tại đơn vị sẽ hướng dẫn bạn nộp hồ sơ, quay video và bình chọn.</p>

      <div class="sec-head" style="margin-top:56px"><h2>Hồ sơ ứng viên gồm những gì?</h2></div>
      <div class="profile">
        <article class="card">
          <h3>Thông tin ứng viên</h3>
          <ul><li>Họ tên, mã nhân viên</li><li>Phòng ban, đơn vị</li><li>Email</li></ul>
        </article>
        <article class="card">
          <h3>Câu chuyện MOVE</h3>
          <p>Kể theo bốn ý, nêu rõ bạn đã chủ động làm gì và điều gì đã thay đổi nhờ việc đó.</p>
          <div class="star"><span>Bối cảnh</span><span>Nhiệm vụ</span><span>Hành động</span><span>Kết quả</span></div>
        </article>
        <article class="card">
          <h3>Video 60 – 90 giây (bắt buộc)</h3>
          <p>Chọn một trong ba hướng:</p>
          <ul><li>Giới thiệu bản thân và một ngày làm việc</li><li>Chia sẻ điều bạn muốn thay đổi ở AgriS</li><li>Kể chính câu chuyện MOVE của bạn</li></ul>
        </article>
      </div>
      <p class="fine">Bạn được dùng AI để hỗ trợ viết và dựng video, nhưng thông tin phải đúng sự thật. Khi nộp hồ sơ, bạn đồng ý để AgriS sử dụng câu chuyện, hình ảnh và video cho mục đích truyền thông nội bộ.</p>
    </div>
  </section>

  <section class="sec mint" id="tieu-chi" aria-labelledby="t-tieuchi">
    <div class="in">
      <div class="sec-head">
        <h2 id="t-tieuchi">Tiêu chí</h2>
        <p>Hồ sơ được thẩm định qua hai vòng trước khi đăng lên website.</p>
      </div>
      <div class="rounds">
        <article class="card round">
          <span class="tag">Vòng 1 · HR đơn vị</span>
          <h3>Điều kiện ứng viên</h3>
          <ul>
            <li>Đang làm việc tại AgriS</li>
            <li>Không có quyết định kỷ luật hay vi phạm quy tắc ứng xử trong niên độ</li>
            <li>Không có kết quả KPI Không đạt ở cả hai kỳ gần nhất</li>
          </ul>
        </article>
        <article class="card round">
          <span class="tag">Vòng 2 · Ban Tổ chức</span>
          <h3>Câu chuyện MOVE</h3>
          <p>Câu chuyện nêu rõ hành động và kết quả, đồng thời thể hiện ít nhất 1 trong 4 tinh thần MOVE. Câu chuyện cho thấy người đó…</p>
          <div class="move">
            <div><b>Action</b><span>Chủ động hành động, tham gia các hoạt động nội bộ mà không đợi được giao hay nhắc.</span></div>
            <div><b>Innovation</b><span>Thử một cách làm mới hoặc khác trước, dù chỉ là cải tiến nhỏ.</span></div>
            <div><b>Impact</b><span>Tạo ra thay đổi cụ thể cho đội nhóm, khách hàng hoặc đơn vị.</span></div>
            <div><b>As One</b><span>Phối hợp, hỗ trợ, chia sẻ và lan toả đến đồng nghiệp, đơn vị khác.</span></div>
          </div>
        </article>
      </div>
      <p class="fine">Hồ sơ hợp lệ được đăng lên website. Hồ sơ cần bổ sung sẽ được gửi lại kèm gợi ý; sau 10 ngày không phản hồi, hồ sơ được xem là không hợp lệ.</p>
    </div>
  </section>

  <section class="sec" id="binh-chon" aria-labelledby="t-binhchon">
    <div class="in">
      <div class="sec-head">
        <h2 id="t-binhchon">Bình chọn</h2>
        <p>Từ 01/03 đến 30/04/2027, trên website www.agrismove.com.</p>
      </div>
      <div class="vote">
        <article class="card">
          <div class="rule"><span class="n">1</span><p>Mỗi CBNV dùng tài khoản định danh theo mã nhân viên, không cần mật khẩu.</p></div>
          <div class="rule"><span class="n">2</span><p>Bạn được bình chọn cho nhiều MOVER, mỗi MOVER tối đa một lượt “love”; mọi lượt bình chọn có giá trị như nhau.</p></div>
          <div class="rule"><span class="n">3</span><p>Hãy chia sẻ câu chuyện bạn yêu thích và mời đồng nghiệp cùng bình chọn.</p></div>
        </article>
        <article class="card warn">
          <h3>Bình chọn công bằng</h3>
          <p>Chỉ dùng tài khoản của chính mình. Nếu phát hiện tài khoản của bạn bị người khác sử dụng, hãy báo Ban Tổ chức để huỷ kết quả bình chọn đó.</p>
          <p>Nghiêm cấm dùng tài khoản của người khác, công cụ bình chọn tự động hoặc trao đổi lượt bình chọn. Vi phạm có thể bị huỷ lượt bình chọn, loại khỏi chương trình hoặc thu hồi danh hiệu.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="sec mint" id="giai-thuong" aria-labelledby="t-giai">
    <div class="in">
      <div class="sec-head"><h2 id="t-giai">Giải thưởng</h2></div>
      <div class="prizes">
        <article class="prize gold">
          <span class="who">50 MOVER có lượt bình chọn cao nhất toàn hệ thống</span>
          <h3>TOP 50 AgriS MOVERS</h3>
          <div class="slot"><span><b>Ảnh cần bổ sung</b>Cúp ABI TOP 50 MOVERS (ảnh chụp hoặc render, nền trong suốt)</span></div>
          <ul>
            <li><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.2 6.8.8-5 4.6 1.3 6.7L12 17l-6 3.3 1.3-6.7-5-4.6 6.8-.8z"></path></svg>Thư chúc mừng của Ban Lãnh đạo</li>
            <li><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.2 6.8.8-5 4.6 1.3 6.7L12 17l-6 3.3 1.3-6.7-5-4.6 6.8-.8z"></path></svg>Cúp ABI TOP 50 MOVERS</li>
            <li><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.2 6.8.8-5 4.6 1.3 6.7L12 17l-6 3.3 1.3-6.7-5-4.6 6.8-.8z"></path></svg>Áo thun phiên bản đặc biệt</li>
            <li><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.2 6.8.8-5 4.6 1.3 6.7L12 17l-6 3.3 1.3-6.7-5-4.6 6.8-.8z"></path></svg>Vinh danh tại sự kiện chính thức</li>
            <li><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.2 6.8.8-5 4.6 1.3 6.7L12 17l-6 3.3 1.3-6.7-5-4.6 6.8-.8z"></path></svg>Cơ hội trở thành đại diện văn hoá trong các chương trình nội bộ khác</li>
            <li><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.2 6.8.8-5 4.6 1.3 6.7L12 17l-6 3.3 1.3-6.7-5-4.6 6.8-.8z"></path></svg>Câu chuyện được lưu tại Hall of Fame</li>
          </ul>
        </article>
        <article class="prize green">
          <span class="who">MOVER ngoài TOP 50 đạt từ 100 lượt bình chọn</span>
          <h3>AgriS’ Choice</h3>
          <div class="slot"><span><b>Ảnh cần bổ sung</b>Thiệp chúc mừng AgriS’ Choice (mockup)</span></div>
          <ul>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>Thiệp chúc mừng</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>Được vinh danh trong danh sách AgriS’ Choice 2027</li>
          </ul>
        </article>
      </div>
    </div>
  </section>

  <?php if ( shortcode_exists( 'amv_movers' ) ) : ?>
  <section class="sec" id="cac-mover" aria-labelledby="t-mover">
    <div class="in">
      <div class="sec-head">
        <h2 id="t-mover">Các MOVER</h2>
        <p>Đọc câu chuyện MOVE của đồng nghiệp và thả “love” cho những câu chuyện bạn yêu thích.</p>
      </div>
      <?php echo do_shortcode( '[amv_movers]' ); // phpcs:ignore ?>
    </div>
  </section>
  <?php endif; ?>

  <section class="sec mint" id="hoi-dap" aria-labelledby="t-faq">
    <div class="in">
      <div class="sec-head"><h2 id="t-faq">Hỏi nhanh – đáp gọn</h2></div>
      <div class="faq">
        <details><summary>Tôi có thể tự ứng cử không?</summary><p>Có. Chương trình khuyến khích bạn tự tin kể câu chuyện MOVE của chính mình.</p></details>
        <details><summary>Tôi được đồng nghiệp hoặc đơn vị đề cử thì cần làm gì?</summary><p>Ban Tổ chức sẽ gửi lời mời và hướng dẫn. Bạn tự hoàn thiện và nộp hồ sơ trên website trước ngày 28/02/2027.</p></details>
        <details><summary>Kết quả TOP 50 được xác định thế nào?</summary><p>Theo tổng lượt bình chọn hợp lệ đến hết ngày 30/04/2027. Nếu nhiều ứng viên bằng lượt ở vị trí thứ 50: từ 3 người trở xuống đều vào TOP 50; từ 4 người trở lên, ưu tiên người nộp hồ sơ sớm hơn. Khiếu nại gửi qua email Ban Tổ chức từ 01 – 10/05/2027; quyết định của Hội đồng xác nhận kết quả là quyết định cuối cùng.</p></details>
        <details><summary>Tôi làm việc tại nhà máy, vùng nguyên liệu thì tham gia thế nào?</summary><p>HR và bộ phận truyền thông nội bộ tại đơn vị sẽ hướng dẫn bạn nộp hồ sơ, quay video và bình chọn.</p></details>
      </div>
    </div>
  </section>

  <div class="in contact">
    <div class="box">
      <h2>Liên hệ Ban Tổ chức</h2>
      <div class="meta"><span>Email: [email]</span><span>Điện thoại: [số điện thoại]</span><span>Website: www.agrismove.com</span></div>
    </div>
  </div>
</main>
	<?php
endwhile;
get_footer();
