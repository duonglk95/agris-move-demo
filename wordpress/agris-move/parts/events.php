<?php
/** Live + upcoming event groups; used on the homepage and the Sự kiện page. */
?>
      <?php
      $evg = agm_event_groups();
      $wd  = array( 'CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7' );
      if ( ! $evg['live'] && ! $evg['soon'] ) :
        ?><p class="ev-empty" data-rv>Sự kiện mới sẽ sớm cập nhật.</p><?php
      endif;
      if ( $evg['live'] ) :
        ?>
      <div class="ev-group" data-rv=".08">
        <h3 class="ev-gh"><span class="ev-dot"></span>Đang diễn ra</h3>
        <?php foreach ( $evg['live'] as $e ) : $tag = $e['url'] ? 'a' : 'div'; ?>
        <<?php echo $tag; // phpcs:ignore ?> class="ev-live"<?php echo $e['url'] ? ' href="' . esc_url( $e['url'] ) . '"' : ''; // phpcs:ignore ?>>
          <div class="ev-img"><?php if ( $e['img'] ) : ?><img src="<?php echo esc_url( $e['img'] ); ?>" alt=""><?php endif; ?></div>
          <div class="ev-body">
            <span class="ev-pill live">Đang diễn ra</span>
            <h4><?php echo esc_html( $e['title'] ); ?></h4>
            <p class="ev-meta">Đến <?php echo esc_html( wp_date( 'd/m/Y', $e['end'] ) ); ?><?php echo $e['place'] ? ' · ' . esc_html( $e['place'] ) : ''; ?></p>
            <?php if ( $e['count'] ) : ?><p class="ev-meta"><?php echo esc_html( number_format_i18n( $e['count'] ) ); ?> lời cảm ơn đã lên tường</p><?php endif; ?>
            <?php if ( $e['url'] ) : ?><span class="btn">Tham gia <?php echo agm_arrow( 'chev' ); // phpcs:ignore ?></span><?php endif; ?>
          </div>
        </<?php echo $tag; // phpcs:ignore ?>>
        <?php endforeach; ?>
      </div>
      <?php endif; if ( $evg['soon'] ) : ?>
      <div class="ev-group" data-rv=".16">
        <h3 class="ev-gh">Sắp diễn ra</h3>
        <div class="ev-soon">
          <?php foreach ( array_slice( $evg['soon'], 0, 6 ) as $e ) :
            $days = (int) ceil( ( $e['start'] - time() ) / DAY_IN_SECONDS ); $tag = $e['url'] ? 'a' : 'div'; ?>
          <<?php echo $tag; // phpcs:ignore ?> class="ev-card"<?php echo $e['url'] ? ' href="' . esc_url( $e['url'] ) . '"' : ''; // phpcs:ignore ?>>
            <div class="ev-date"><b><?php echo esc_html( wp_date( 'd', $e['start'] ) ); ?></b><span>Th<?php echo esc_html( wp_date( 'n', $e['start'] ) ); ?> · <?php echo esc_html( $wd[ (int) wp_date( 'w', $e['start'] ) ] ); ?></span></div>
            <div>
              <span class="ev-pill"><?php echo $days <= 1 ? 'Ngày mai' : 'Còn ' . esc_html( $days ) . ' ngày'; ?></span>
              <h4><?php echo esc_html( $e['title'] ); ?></h4>
              <?php if ( $e['place'] ) : ?><p class="ev-meta"><?php echo esc_html( $e['place'] ); ?></p><?php endif; ?>
            </div>
          </<?php echo $tag; // phpcs:ignore ?>>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
