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
                        if ($hero) {
                            // ACF: 画像ID or 配列どちらでもOKにする
                            if (is_numeric($hero)) echo wp_get_attachment_image((int)$hero, 'large', false, ['class' => 'fv']);
                            elseif (is_array($hero) && !empty($hero['ID'])) echo wp_get_attachment_image((int)$hero['ID'], 'large', false, ['class' => 'fv']);
                            elseif (is_array($hero) && !empty($hero['url'])) echo '<img class="fv" src="' . esc_url($hero['url']) . '" alt="">';
                        } elseif (has_post_thumbnail()) {
                            the_post_thumbnail('large', ['class' => 'fv', 'alt' => esc_attr(get_the_title())]);
                        }
                        ?>
                    </div>

                    <div class="icons">
                        <a href="#" data-action="open-comments">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/img/cards/Comment icon.png'); ?>" alt="コメント">
                        </a>
                        <a href="#">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/img/cards/Like icon.png'); ?>" alt="いいね">
                        </a>
                        <a href="#">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/img/cards/Share icon.png'); ?>" alt="シェア">
                        </a>
                    </div>

                    <div id="tags">
                        <h2>Tags</h2>
                        <div class="flex-tags">
                            <!-- WPのタグ -->
                            <ul class="tags">
                                <?php
                                $tags = get_the_tags();
                                if ($tags) {
                                    foreach ($tags as $t) {
                                        echo '<li><span class="tag">' . esc_html($t->name) . '</span></li>';
                                    }
                                }
                                ?>
                            </ul>

                            <!-- 外部リンク（ACF: site_url / github_url） -->
                            <ul class="links">
                                <?php
                                $site = function_exists('get_field') ? get_field('site_url')   : get_post_meta(get_the_ID(), 'site_url', true);
                                $git  = function_exists('get_field') ? get_field('github_url') : get_post_meta(get_the_ID(), 'github_url', true);
                                if ($site) echo '<li><a class="tag" href="' . esc_url($site) . '" target="_blank" rel="noopener">Visit Web Site</a></li>';
                                if ($git) echo '<li><a class="tag" href="' . esc_url($git) . '" target="_blank" rel="noopener">GitHub</a></li>';
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
                                // ACF: overview があれば優先表示、無ければ WP 本文にフォールバック
                                if (function_exists('get_field')) {
                                    $overview = get_field('overview');
                                } else {
                                    $overview = '';
                                }

                                if ($overview) {
                                    // WYSIWYG ならそのまま安全に出力
                                    echo wp_kses_post($overview);
                                } else {
                                    // フォールバック：本文
                                    the_content();
                                }
                                ?>
                            </div>
                        </div>
                    </section>

                    <!-- ===== ターゲット/ペルソナ（ACF: persona） ===== -->
                    <?php
                    $persona = function_exists('get_field') ? get_field('persona') : get_post_meta(get_the_ID(), 'persona', true);
                    if ($persona) : ?>
                        <section class="project-info">
                            <h2>ターゲット/ペルソナ</h2>
                            <div class="project-info-card">
                                <div class="project-info-body">
                                    <?php echo wp_kses_post(is_string($persona) ? wpautop($persona) : $persona); ?>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>

                    <!-- ===== こだわりポイント（ACF: highlights） ===== -->
                    <?php
                    $highlights = function_exists('get_field') ? get_field('highlights') : get_post_meta(get_the_ID(), 'highlights', true);
                    if ($highlights) : ?>
                        <section class="project-info">
                            <h2>こだわりポイント</h2>
                            <div class="project-info-card">
                                <div class="project-info-body">
                                    <?php echo wp_kses_post(is_string($highlights) ? wpautop($highlights) : $highlights); ?>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>

                    <!-- ===== サイト全体像（ACF: full_screenshot） ===== -->
                    <section id="screenshots">
                        <h2 class="sec-title">サイト全体像</h2>
                        <?php
                        $shot = function_exists('get_field') ? get_field('full_screenshot') : get_post_meta(get_the_ID(), 'full_screenshot', true);
                        if ($shot) {
                            if (is_numeric($shot)) {
                                echo wp_get_attachment_image((int)$shot, 'xlarge');
                            } elseif (is_array($shot) && !empty($shot['ID'])) {
                                echo wp_get_attachment_image((int)$shot['ID'], 'xlarge');
                            } else {
                                echo '<img src="' . esc_url($shot) . '" alt="">';
                            }
                        }
                        ?>
                    </section>
                </div>

                <!-- 前後ナビ（任意） -->
                <nav class="detail-nav wrapper">
                    <div class="prev"><?php previous_post_link('%link'); ?></div>
                    <div class="next"><?php next_post_link('%link'); ?></div>
                </nav>

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
<?php get_footer();
