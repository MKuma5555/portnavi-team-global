<?php get_header(); ?>

<main>
  <div class="event-container" role="main">
    <section class="cards event-cards" aria-label="イベントカード">
      <?php
      $paged = get_query_var('paged') ? get_query_var('paged') : 1; 
      $args = array(
        'post_type'      => 'eventpost',
        'posts_per_page' => 12,
        'orderby'        => 'date',
        'order'          => 'ASC',
        'paged'          => $paged, 
      );
      $the_query = new WP_Query($args);
      ?>

      <?php if ($the_query->have_posts()) : ?>
        <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
          <article class="event-card">
            <div class="card__body">
              <figure class="card__img">
                <?php if (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('medium'); ?>
                <?php else : ?>
                  <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/cards/sp-sun.png" alt="デフォルト画像">
                <?php endif; ?>
              </figure>

              <div class="event-txt">
                <h2 class="card__title"><?php the_title(); ?></h2>

                <?php if (get_field('event_period_start') || get_field('event_period_end')) : ?>
                  <p class="date">
                    制作期間：
                    <?php if (get_field('event_period_start')) : ?>
                      <?php the_field('event_period_start'); ?>
                    <?php endif; ?>
                    <?php if (get_field('event_period_end')) : ?>
                      ~ <?php the_field('event_period_end'); ?>
                    <?php endif; ?>
                  </p>
                <?php endif; ?>

                <?php if (get_field('event_theme')) : ?>
                  <p class="theme">テーマ：<?php the_field('event_theme'); ?></p>
                <?php endif; ?>

                <?php if (get_field('text')) : ?>
                  <p class="desc"><?php the_field('text'); ?></p>
                <?php endif; ?>

                <div class="card__actions">
                  <?php if (get_field('urlevent_button_url')) : ?>
                    <a class="btn" href="<?php the_field('urlevent_button_url'); ?>" target="_blank" rel="noopener">
                      <?php the_field('event_button_label'); ?>
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </article>
        <?php endwhile; ?>

        <!-- ページネーション -->
        <div class="navigation pagination">
          <?php
          the_posts_pagination(array(
            'mid_size'  => 1,
            'prev_text' => '« 前へ',
            'next_text' => '次へ »',
          ));
          ?>
        </div>

      <?php else : ?>
        <p>投稿が見つかりませんでした。</p>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>
    </section>
  </div>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
