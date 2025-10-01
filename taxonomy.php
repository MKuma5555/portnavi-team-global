<?php

/**
 * 汎用タクソノミーアーカイブ
 * 対応: site_type / design_type / color / tech_stack / category / post_tag 
 */
get_header();
get_sidebar();

$term    = get_queried_object();
$heading = $term && isset($term->name) ? esc_html($term->name) : 'アーカイブ';
$desc    = term_description($term, $term->taxonomy);
$paged   = max(1, (int) get_query_var('paged'));
?>

<main class="page-content">

    <div class="wrapper info-top">
        <p class="info-details">
            <?php echo $heading; ?><br />
            検索結果
        </p>
        <p class="info-underline"></p>
        <?php if ($desc) : ?>
            <div class="taxonomy-description"><?php echo wp_kses_post($desc); ?></div>
        <?php endif; ?>
    </div>

    <div class="wrapper">
        <ul class="cards-list">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <li class="card-container">
                        <div class="card">
                            <a href="<?php the_permalink(); ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium', array('class' => 'card-image')); ?>
                                <?php endif; ?>
                            </a>

                            <div class="card-body">
                                <div class="card-top">
                                    <a href="<?php the_permalink(); ?>">
                                        <h3 class="card-title"><?php the_title(); ?></h3>
                                    </a>
                                    <ul class="card-tags">
                                        <?php
                                        $categories = get_the_category();
                                        if (! empty($categories)) {
                                            foreach ($categories as $cat) {
                                                echo '<li><p class="card-tag-list">' . esc_html($cat->name) . '</p></li>';
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>

                                <p class="card-text"><?php echo get_the_excerpt(); ?></p>

                                <div class="card-bottom">
                                    <p class="post-date"><?php echo get_the_date('Y/m/d'); ?></p>
                                    <div class="card-icons">
                                        <?php if (function_exists('wp_ulike')) {
                                            wp_ulike();
                                        } ?>
                                        <div class="post-view">
                                            <!-- 目アイコンは必要ならSVGを置いてね -->
                                            <a href="<?php the_permalink(); ?>" class="view">
                                                <?php if (function_exists('the_views')) {
                                                    the_views(false);
                                                } ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->
                    </li>
                <?php endwhile; ?>
            <?php else : ?>
                <p>該当する投稿が見当たりませんでした</p>
            <?php endif; ?>
        </ul>

        <?php
        // ページネーション
        the_posts_pagination(array(
            'mid_size'  => 1,
            'prev_text' => '«',
            'next_text' => '»',
        ));
        ?>
    </div>
</main>

<?php get_footer(); ?>