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
            <!-- <?php echo $heading; ?><br /> -->
            以下、検索結果をご覧ください<br />
条件に一致したサイトがこちらです<br />
気になる作品をクリックして詳細をチェック！<br />
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
                        <!-- <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium', array( 'class' => 'card-image' ) ); ?>
                        <?php endif; ?> -->
                        <?php
                $image_displayed = false;
                // 1. ACF: 'hero_image' フィールドから画像を取得
                if (function_exists('get_field')) {
                    $hero_image = get_field('hero_image');

                    if ($hero_image) {
                        // ACFフィールドの値が画像IDの場合（推奨される戻り値）
                        if (is_numeric($hero_image)) {
                            echo wp_get_attachment_image((int)$hero_image, 'medium', false, ['class' => 'card-image']);
                            $image_displayed = true;
                        }
                        // ACFフィールドの値が画像配列の場合
                        elseif (is_array($hero_image) && !empty($hero_image['ID'])) {
                            echo wp_get_attachment_image((int)$hero_image['ID'], 'medium', false, ['class' => 'card-image']);
                            $image_displayed = true;
                        }
                        // ACFフィールドの値が画像URLの場合
                        elseif (is_array($hero_image) && !empty($hero_image['url'])) {
                            echo '<img class="card-image " src="' . esc_url($hero_image['url']) . '" alt="' . esc_attr(get_the_title()) . '">';
                            $image_displayed = true;
                        }
                    }
                }

                // 2. ACF画像が取得できなかった場合、アイキャッチ画像を表示
                if (!$image_displayed && has_post_thumbnail()) {
                    the_post_thumbnail('medium', ['class' => 'card-image', 'alt' => esc_attr(get_the_title())]);
                    $image_displayed = true;
                }

                // 3. どちらもなかった場合、ダミー画像を表示
                if (!$image_displayed) {
                    ?>
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/img/cards/dummy-300X200.png' ); ?>" alt="<?php the_title(); ?>" class="card-image">
                    <?php
                }
                ?>

                            <div class="card-body">
                                <div class="card-top">
                                    <a href="<?php the_permalink(); ?>">
                                        <h3 class="card-title"><?php the_title(); ?></h3>
                                    </a>
                                    <ul class="card-tags">
                                    <?php
                      // //タクソノミーで作成しているカテゴリーとタグ等
                      $post_id = get_the_ID();

                      // design_type
                      $design_types = get_the_terms($post_id, 'design_type');
                      if ( !empty($design_types) && !is_wp_error($design_types) ) {
                        // デザインの種類の一番初めのデータのタグのみ表示
                        $first_term = $design_types[0];
                            echo '<li><p class="card-tag-list">' . esc_html($first_term->name) . '</p></li>';
                      }
                    ?>
                                    </ul>
                                </div>

                                <p class="card-text">
                                <?php
                  // ACFがあればそれを取得、なければ空文字列
                  $text = function_exists('get_field') ? get_field('overview') : '';
                  if (empty($text)) {
                      $content = get_the_content();
                      $text = wp_strip_all_tags(strip_shortcodes($content));
                  }
                  
                  $limit = 150;
                  $length = mb_strlen($text);

                  // 150文字を超えていたら切り詰め、「...」を付けて安全に出力
                  if ($length > $limit) {
                      echo esc_html(mb_substr($text, 0, $limit) . '...');
                  } else {
                      // それ以外はそのまま安全に出力
                      echo esc_html($text); 
                  }
                  ?>
                                </p>

                                <div class="card-bottom">
                            <p class="post-date"><?php echo get_the_date('Y/m/d'); ?></p>
                            <div class="card-icons">
                                <!-- いいねボタン -->
                                    <?php if ( function_exists( 'wp_ulike' ) ) { wp_ulike(); } ?>
                                <!-- Clickボタン -->
                                <div class=post-view>
                      <div class=post-view-icon><a href="<?php the_permalink(); ?>" class="view">
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" width="24" height="24" color="#616161">
                            <defs><style>.cls-637b8170f95e86b59c57a03a-1{fill:none;stroke:currentColor;stroke-miterlimit:10;}</style></defs>
                            <g id="eye"><path class="cls-637b8170f95e86b59c57a03a-1" d="M22.5,12A12.24,12.24,0,0,1,12,17.73,12.24,12.24,0,0,1,1.5,12,12.24,12.24,0,0,1,12,6.27,12.24,12.24,0,0,1,22.5,12Z"></path>
                            <circle class="cls-637b8170f95e86b59c57a03a-1" cx="12" cy="12" r="5.73"></circle>
                            <circle class="cls-637b8170f95e86b59c57a03a-1" cx="12" cy="12" r="1.91"></circle></g>
                          </svg>
                         </a>        
                         <span style="display: inline-block;"><?php if(function_exists('the_views')) { the_views(); } ?></span> 
                      </div>
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