<?php get_header(); ?>

<main class="detail-page">
    <div id="appFrame">

        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                <!-- ===== ファーストビュー ===== -->
                <section id="firstview" class="wrapper">
                    <div class="fv-wrap">
                        <?php
                        // メイン画像（優先順：ACF hero_image → アイキャッチ → 何もなし）
                        $hero = function_exists('get_field') ? get_field('hero_image') : null;
                        $alt  = esc_attr(get_the_title());

                        if ($hero) {
                            // ACF: 画像ID or 配列どちらでもOKにする
                            if (is_numeric($hero)) {
                                echo wp_get_attachment_image((int)$hero, 'large', false, ['class' => 'fv', 'alt' => $alt]);
                            } elseif (is_array($hero) && !empty($hero['ID'])) {
                                echo wp_get_attachment_image((int)$hero['ID'], 'large', false, ['class' => 'fv', 'alt' => $alt]);
                            } elseif (is_array($hero) && !empty($hero['url'])) {
                                echo '<img class="fv" src="' . esc_url($hero['url']) . '" alt="' . $alt . '">';
                            }
                        } elseif (has_post_thumbnail()) {
                            the_post_thumbnail('large', ['class' => 'fv', 'alt' => $alt]);
                        } else {
                            // ★ ACFもアイキャッチもない場合のダミー画像表示
                            echo '<img class="fv" src="' . esc_url(get_template_directory_uri() . '/img/cards/dummy-1920X1080.png') . '" alt="' . $alt . '">';
                        }
                        ?>
                    </div>

                    <div class="icons">
                        <!-- コメント -->
                        <a href="#" data-action="open-comments">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/img/cards/Comment icon.png'); ?>" alt="コメント">
                        </a>

                        <!-- いいね -->
                        <?php if (function_exists('wp_ulike')) : ?>
                            <button type="button"
                                class="like-proxy"
                                data-like-target="wpulike-hidden-<?php the_ID(); ?>"
                                aria-label="この作品にいいね">
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/img/cards/Like icon.png'); ?>" alt="" />
                                <span class="like-count" aria-live="polite">
                                    <?php echo (int) wp_ulike_get_post_likes(get_the_ID()); ?>
                                </span>
                            </button>

                            <!-- 本物の WP ULike ボタン（視覚非表示） -->
                            <div id="wpulike-hidden-<?php the_ID(); ?>" class="visually-hidden" aria-hidden="true">
                                <?php
                                // デフォルトの投稿タイプ(post)に対する ULike ボタンを出力
                                // （プラグインの Ajax・重複制御・カウント更新を利用）
                                wp_ulike('get', array(
                                    'id'       => get_the_ID(),
                                    'counter'  => true,           // カウントも出力（後で値を拾う用）
                                    'logging'  =>  true,
                                    'display_likers' => false
                                ));
                                ?>
                            </div>
                        <?php endif; ?>

                        <!-- シェア -->
                        <a href="#" class="share-open"
                            data-share-url="<?php echo esc_url(get_permalink()); ?>"
                            data-share-title="<?php echo esc_attr(get_the_title()); ?>"
                            data-share-image="<?php echo esc_url(get_the_post_thumbnail_url(null, 'medium')); ?>">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/img/cards/Share icon.png'); ?>" alt="シェア">
                        </a>
                    </div>

                    <div id="tags">
                        <h2>Tags</h2>
                        <div class="flex-tags">


                            <!-- バッジ（カテゴリー/タグ/カスタムタクソノミー/tech_stack） -->
                            <ul class="tags">
                                <?php
                                // ここに表示したい順番で列挙（左→右に出ます）
                                $taxes = [
                                    'tech_stack'   => 'tag-tech',   // 使用技術（既存）
                                    'category'     => 'tag-cat',    // カテゴリー（標準）
                                    'post_tag'     => 'tag-key',    // タグ（標準）
                                    'site_type'    => 'tag-site',   // カスタム：サイト種別
                                    'design_type'  => 'tag-design', // カスタム：デザイン種別
                                    'color'        => 'tag-color',  // カスタム：カラー
                                ];

                                foreach ($taxes as $tax => $extra_class) {
                                    $terms = get_the_terms(get_the_ID(), $tax);
                                    if (!empty($terms) && !is_wp_error($terms)) {
                                        foreach ($terms as $term) {
                                            echo '<li><span class="tag ' . esc_attr($extra_class) . '" data-tax="' . esc_attr($tax) . '">' . esc_html($term->name) . '</span></li>';
                                        }
                                    }
                                }
                                ?>
                            </ul>

                            <!-- 外部リンク（ACF: site_url / github_url） -->
                            <ul class="links">
                                <?php
                                $site = function_exists('get_field') ? get_field('site_url')   : get_post_meta(get_the_ID(), 'site_url', true);
                                $git  = function_exists('get_field') ? get_field('github_url') : get_post_meta(get_the_ID(), 'github_url', true);

                                if ($site) {
                                    echo '<li><a class="link-tag" href="' . esc_url($site) . '" target="_blank" rel="noopener">';
                                    echo '<img class="site-visit" src="' . esc_url(get_template_directory_uri()) . '/img/icons/visit-website.png" alt=""> Visit サイトへ';
                                    echo '</a></li>';
                                }

                                if ($git) {
                                    echo '<li><a class="link-tag" href="' . esc_url($git) . '" target="_blank" rel="noopener">';
                                    echo '<img class="github-visit" src="' . esc_url(get_template_directory_uri()) . '/img/icons/visit-github.png" alt=""> GitHubを見る';
                                    echo '</a></li>';
                                }
                                ?>

                            </ul>
                        </div>
                    </div>
                </section>

                <div class="wrapper">
                    <!-- ===== 概要 ===== -->
                    <section class="project-info">
                        <h2>サイト概要</h2>
                        <div class="project-info-card">
                            <div class="project-info-body">
                                <?php
                                $pid = get_the_ID();

                                // 1) overview（ACF優先）
                                $overview = function_exists('get_field') ? get_field('overview', $pid) : '';
                                $has_overview = strlen(trim(wp_strip_all_tags((string) $overview))) > 0;

                                // 2) 本文の生データ（the_contentは使わない＝ULike混入防止）
                                $raw = (string) get_post_field('post_content', $pid);
                                $has_raw = strlen(trim(wp_strip_all_tags($raw))) > 0;

                                if ($has_overview) {
                                    echo wp_kses_post(is_string($overview) ? wpautop($overview) : $overview);
                                } elseif ($has_raw) {
                                    echo do_shortcode(wpautop($raw));
                                } else {
                                    echo '<p class="no-content">N/A</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </section>

                    <!-- ===== ターゲット/ペルソナ（ACF: persona） ===== -->
                    <section class="project-info">
                        <h2>ターゲット/ペルソナ</h2>
                        <div class="project-info-card">
                            <div class="project-info-body">
                                <?php
                                $persona = function_exists('get_field') ? get_field('persona', $pid) : get_post_meta($pid, 'persona', true);
                                if (strlen(trim(wp_strip_all_tags((string) $persona))) > 0) {
                                    echo wp_kses_post(is_string($persona) ? wpautop($persona) : $persona);
                                } else {
                                    echo '<p class="no-content">N/A</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </section>

                    <!-- ===== こだわりポイント（ACF: highlights） ===== -->
                    <section class="project-info">
                        <h2>こだわりポイント</h2>
                        <div class="project-info-card">
                            <div class="project-info-body">
                                <?php
                                $highlights = function_exists('get_field') ? get_field('highlights', $pid) : get_post_meta($pid, 'highlights', true);
                                if (strlen(trim(wp_strip_all_tags((string) $highlights))) > 0) {
                                    echo wp_kses_post(is_string($highlights) ? wpautop($highlights) : $highlights);
                                } else {
                                    echo '<p class="no-content">N/A</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </section>

                    <!-- ===== サイト全体像（ACF: full_screenshot） ===== -->
                    <section class="project-info">
                        <h2>サイト全体像</h2>
                        <div class="project-info-card">
                            <div class="project-info-body">
                                <?php
                                $pid = get_the_ID();
                                $shot = function_exists('get_field') ? get_field('full_screenshot', $pid) : get_post_meta($pid, 'full_screenshot', true);

                                if ($shot) {
                                    if (is_numeric($shot)) {
                                        echo wp_get_attachment_image((int)$shot, 'xlarge', false, ['alt' => get_the_title()]);
                                    } elseif (is_array($shot) && !empty($shot['ID'])) {
                                        echo wp_get_attachment_image((int)$shot['ID'], 'xlarge', false, ['alt' => get_the_title()]);
                                    } elseif (is_array($shot) && !empty($shot['url'])) {
                                        echo '<img src="' . esc_url($shot['url']) . '" alt="' . esc_attr(get_the_title()) . '">';
                                    } else {
                                        echo '<img src="' . esc_url($shot) . '" alt="' . esc_attr(get_the_title()) . '">';
                                    }
                                } else {
                                    echo '<p class="no-content">N/A</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </section>
                </div>


        <?php endwhile;
        endif; ?>

        <!-- コメントドロワー（開閉はJS） -->
        <aside id="commentDrawer" class="comment-drawer" aria-hidden="true">
            <h2>コメント</h2>
            <button class="drawer-close" aria-label="閉じる"></button>
            <div class="drawer-body">
                <?php if (comments_open() || get_comments_number()) comments_template(); ?>
            </div>
        </aside>

    </div>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>