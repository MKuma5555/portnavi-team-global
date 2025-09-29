<?php get_header(); ?>

<main>
    <div class="event-container" role="main">
        <section class="cards event-cards" aria-label="イベントカード">
            <?php
            $args = array(
                'post_type' => 'eventpost',
                'posts_per_page' => 12,
            );
            $the_query = new WP_Query($args);
            ?>
            <?php if ($the_query->have_posts()): ?>
                <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                    <!-- Card 1 -->
                    <article class="event-card">
                        <div class="card__body">
                            <figure class="card__img">
                                <?php if (has_post_thumbnail()): ?>
                                    <?php the_post_thumbnail('medium'); ?>
                                <?php else: ?>
                                    <!-- アイキャッチ未設定時のデフォルト画像 -->
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/cards/sp-sun.png" alt="デフォルト画像">
                                <?php endif; ?>
                            </figure>

                            <div class="event-txt">
                                <h2 class="card__title"><?php the_title(); ?></h2>
                                <p class="date">制作期間：<?php the_field('event_period_start'); ?> ~ <?php the_field('event_period_end') ?></p>
                                <p class="theme">テーマ：<?php the_field('event_theme'); ?> </p>
                                <p class="desc"><?php the_field('text'); ?></p>
                                <div class="card__actions">
                                    <?php if (get_field('urlevent_button_url')): ?>
                                        <a class="btn" href="<?php the_field('urlevent_button_url'); ?>" target="_blank" rel="noopener">
                                            <?php the_field('event_button_label'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <p>投稿が見つかりませんでした。</p>
            <?php endif;
            wp_reset_postdata(); ?>


        </section>
    </div>

</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>