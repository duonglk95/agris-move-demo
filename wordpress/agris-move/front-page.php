<?php
/**
 * Homepage: hero animation, next event, quarter ticker, TOP 50 banner, AgriS-thing, Bản tin MOVE.
 */
get_header();

$pods = get_posts( array( 'post_type' => 'podcast', 'posts_per_page' => 3, 'post_status' => 'publish' ) );
$news = get_posts( array( 'post_type' => 'post', 'posts_per_page' => 3, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) );

/** cover image for a post, or the bundled demo picture */
$cover = function ( $post, $demo, $size = 'agm-card' ) {
	if ( $post && has_post_thumbnail( $post ) ) {
		return get_the_post_thumbnail_url( $post, $size );
	}
	return agm_img( $demo );
};
?>
<main id="top">
  <div class="hero">
    <div class="stage" id="stage">
    <canvas id="cv" role="img" aria-label="Đàn chim từ bay rời rạc, theo chim đầu đàn vượt bão, che chắn nhau rồi cùng vút vào mũi tên của logo AgriS MOVE"></canvas>
    <img id="corner" alt="" hidden src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
    <img id="lm" alt="" hidden src="<?php echo esc_url( agm_img( 'logo-move-white.png' ) ); ?>">
    <div class="hero-cta"><a class="scroll" href="#su-kien" tabindex="-1" aria-label="Cuộn xuống để xem tiếp"><span>Cuộn xuống</span><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 8l5 5 5-5"></path></svg></a></div>
    <button class="kick" id="k0" type="button" tabindex="-1" aria-label="Lãnh đạo làm gương: xem câu chuyện" aria-describedby="story" aria-expanded="false"></button>
    <button class="kick" id="k1" type="button" tabindex="-1" aria-label="Môi trường tích cực: xem câu chuyện" aria-describedby="story" aria-expanded="false"></button>
    <button class="kick" id="k2" type="button" tabindex="-1" aria-label="Đội ngũ cùng hành động: xem câu chuyện" aria-describedby="story" aria-expanded="false"></button>
    <div class="story" id="story" role="tooltip" hidden>
      <canvas id="mini" aria-hidden="true"></canvas>
      <div class="txt"><span class="k" id="storyKicker"></span><strong class="h2" id="storyTitle"></strong><p id="storyBody"></p></div>
    </div>
    <div hidden><button id="play" type="button">Tạm dừng</button></div>
    <button class="replay" id="replay" type="button" aria-label="Xem lại từ đầu" title="Xem lại từ đầu"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7"></path><path d="M3 4v5h5"></path></svg></button>
    <div id="chapters" hidden></div>
    </div>
  </div>

  <div class="seam" data-next="#e8f4ec" data-h="96" data-tones="#0f513dd8,#2e8a63b8" data-line="lime"></div>
  <section class="band band-glow" id="su-kien">
    <div class="in stack">
      <div class="event-head" data-rv><h2>Sự kiện</h2></div>
      <?php get_template_part( 'parts/events' ); ?>
    </div>
  </section>

  <?php
  $q_now  = get_theme_mod( 'agm_quarter', 'action' );
  $themes = array( 'innovation' => 'MOVE INNOVATION', 'action' => 'MOVE ACTION', 'impact' => 'MOVE IMPACT', 'as-one' => 'MOVE AS ONE', 'cf' => 'CREATE FORWARD' );
  ?>
  <div class="seam" data-next="#0b6b3a" data-h="72" data-tones="#bfe6ccd0,#2faa5ab0" data-line="lime"></div>
  <div class="ticker" aria-hidden="true"><div class="ticker-row"><?php
	for ( $i = 0; $i < 4; $i++ ) {
		foreach ( $themes as $key => $label ) {
			echo '<span' . ( $key === $q_now ? ' class="now"' : '' ) . '>' . esc_html( $label ) . '</span>';
		}
	}
  ?></div></div>
  <div class="seam" data-next="#f8fcf9" data-h="112" data-tones="#2faa5ab0,#9bd8b1d0" data-line="lime"></div>
  <?php if ( agm_show( 'top50' ) ) : ?>
  <section class="band movers" id="movers">
    <div class="in">
      <figure class="top50">
        <img src="<?php echo esc_url( agm_top50_banner() ); ?>" alt="AgriS TOP 50 MOVERS" width="1600" height="900">
        <div class="top50-txt" data-rv=".2">
          <p><?php echo esc_html( get_theme_mod( 'agm_top50_text', 'Mỗi cá nhân đều là một “động lực tăng trưởng”. TOP 50 MOVERS vinh danh những con người không ngừng chủ động kiến tạo giá trị, đại diện cho văn hoá MOVE của AgriS.' ) ); ?></p>
          <a class="more" href="<?php echo esc_url( agm_top50_url() ); ?>">Khám phá câu chuyện <?php echo agm_arrow(); // phpcs:ignore ?></a>
        </div>
      </figure>
    </div>
  </section>
  <?php endif; ?>

  <div class="seam" data-next="#e8f4ec" data-h="84" data-tones="#d3eadb,#ffffffc0"></div>
  <section class="band band-glow pod" id="agris-thing">
    <div class="ribbon" data-ribbon data-px="-.12"></div>
    <div class="in stack">
      <div class="stack" style="gap:12px" data-rv>
        <h2>AgriS-thing</h2>
        <p class="lede">Mỗi tháng, AgriS-thing lại có một khách mời đặc biệt cùng những câu chuyện về cuộc sống, môi trường làm việc tại AgriS và nhiều điều thú vị hơn thế… Ask me anything!</p>
      </div>
      <?php
      $demo_pods = array(
          array( 'Từ ý tưởng đến hành động', 'demo-pod-big.jpg', '24:16' ),
          array( 'Một phút cùng MOVE', 'demo-pod-a.jpg', '01:00' ),
          array( 'Câu chuyện đồng đội', 'demo-pod-b.jpg', '08:42' ),
      );
      $pod = function ( $i ) use ( $pods, $demo_pods, $cover ) {
          $p = isset( $pods[ $i ] ) ? $pods[ $i ] : null;
          return array(
              'title' => $p ? get_the_title( $p ) : $demo_pods[ $i ][0],
              'url'   => $p ? get_permalink( $p ) : agm_podcast_url(),
              'img'   => agm_pod_cover( $p, agm_img( $demo_pods[ $i ][1] ) ),
              'time'  => $p ? get_post_meta( $p->ID, '_agm_duration', true ) : $demo_pods[ $i ][2],
          );
      };
      $p0 = $pod( 0 );
      ?>
      <div class="pod-grid">
        <div data-rv=".1">
          <a class="thumb big" href="<?php echo esc_url( $p0['url'] ); ?>" aria-label="Phát tập: <?php echo esc_attr( $p0['title'] ); ?>"><img alt="" src="<?php echo esc_url( $p0['img'] ); ?>"><span class="chip">Tập mới nhất</span><span class="playb"></span><?php if ( $p0['time'] ) : ?><span class="time"><?php echo esc_html( $p0['time'] ); ?></span><?php endif; ?></a>
          <p class="cap"><?php echo esc_html( $p0['title'] ); ?></p>
          <a class="btn" style="margin-top:18px" href="<?php echo esc_url( $p0['url'] ); ?>">Xem tập này <?php echo agm_arrow( 'chev' ); // phpcs:ignore ?></a>
        </div>
        <div class="side" data-rv=".25">
          <?php for ( $i = 1; $i <= 2; $i++ ) : $pi = $pod( $i ); ?>
          <div><a class="thumb sm" href="<?php echo esc_url( $pi['url'] ); ?>" aria-label="Phát: <?php echo esc_attr( $pi['title'] ); ?>"><img alt="" src="<?php echo esc_url( $pi['img'] ); ?>"><span class="playb"></span><?php if ( $pi['time'] ) : ?><span class="time"><?php echo esc_html( $pi['time'] ); ?></span><?php endif; ?></a><p class="cap"><?php echo esc_html( $pi['title'] ); ?></p></div>
          <?php endfor; ?>
          <a class="more" href="<?php echo esc_url( agm_podcast_url() ); ?>">Xem tất cả video <?php echo agm_arrow(); // phpcs:ignore ?></a>
        </div>
      </div>
    </div>
  </section>

  <div class="seam" data-next="#fbfdfb" data-h="112" data-tones="#d3eadb,#ffffffc0"></div>
  <section class="band" id="ban-tin">
    <div class="in stack">
      <div class="stack" style="gap:12px" data-rv>
        <h2>Bản tin MOVE</h2>
        <p class="lede">AgriS không ngừng chuyển mình để kiến tạo một hệ sinh thái nông nghiệp công nghệ cao, hiện đại và phát triển bền vững. Cùng khám phá những chuyển động của toàn hệ thống.</p>
      </div>
      <div class="news">
        <?php
        $demo_news = array(
            array( 'Cách làm mới trong công việc', 'demo-news-1.jpg', 'Đổi mới' ),
            array( 'Kết nối sức mạnh đồng đội', 'demo-news-2.jpg', 'Đồng đội' ),
            array( 'Những chuyển động tích cực', 'demo-news-3.jpg', 'Vùng nguyên liệu' ),
        );
        $blog = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/?post_type=post' );
        for ( $i = 0; $i < 3; $i++ ) :
            $n     = isset( $news[ $i ] ) ? $news[ $i ] : null;
            $title = $n ? get_the_title( $n ) : $demo_news[ $i ][0];
            $url   = $n ? get_permalink( $n ) : $blog;
            $cats  = $n ? get_the_category( $n->ID ) : array();
            $chip  = $n ? ( $cats ? $cats[0]->name : '' ) : $demo_news[ $i ][2];
            $date  = $n ? get_the_date( 'd/m/Y', $n ) : '';
            ?>
            <article data-rv="<?php echo esc_attr( 0.05 + $i * 0.13 ); ?>"><a class="thumb card" href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( $title ); ?>"><img alt="" src="<?php echo esc_url( $cover( $n, $demo_news[ $i ][1] ) ); ?>"><?php if ( $chip ) : ?><span class="chip"><?php echo esc_html( $chip ); ?></span><?php endif; ?></a><?php if ( $date ) : ?><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $n ) ); ?>"><?php echo esc_html( $date ); ?></time><?php endif; ?><h3><?php echo esc_html( $title ); ?></h3><a class="more" href="<?php echo esc_url( $url ); ?>">Xem thêm <?php echo agm_arrow(); // phpcs:ignore ?></a></article>
        <?php endfor; ?>
      </div>
    </div>
  </section>
</main>
<?php
get_footer();
