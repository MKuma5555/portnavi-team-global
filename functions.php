<?php
// すべてのアセット（スタイルとスクリプト）を読み込む単一の関数
function my_theme_assets()
{
    // スタイルを読み込む
    wp_enqueue_style(
        'reset_style',
        'https://unpkg.com/ress/dist/ress.min.css',
        [],
        '1.0'
    );
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Monoton&family=Zen+Kaku+Gothic+New:wght@400;500;700&family=Noto+Sans+JP:wght@400;700&family=Noto+Sans:wght@400;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'main_style',
        get_template_directory_uri() . '/css/main.css',
        ['reset_style'],
        '1.0'
    );
    wp_enqueue_style(
        'index_style',
        get_template_directory_uri() . '/css/index.css',
        ['reset_style', 'main_style'],
        '1.0'
    );
    wp_enqueue_style(
        'detail_style',
        get_template_directory_uri() . '/css/details.css',
        ['reset_style', 'main_style'],
        '1.0'
    );

    wp_enqueue_style(
        'frontpage_style',
        get_template_directory_uri() . '/css/frontpage.css',
        ['reset_style', 'main_style'],
        '1.0'
    );


    if (is_page('event')) {
        wp_enqueue_style(
            'event_style',
            get_template_directory_uri() . '/css/event.css',
            ['reset_style', 'main_style'],
            '1.0'
        );
    }

    // スクリプトを読み込む
    // デフォルトのjQueryを削除し、CDNから読み込む
    wp_deregister_script('jquery');
    wp_enqueue_script(
        'jquery',
        'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js',
        [],
        '3.6.0',
        true
    );

    // search.js を読み込む
    wp_enqueue_script(
        'search-script',
        get_template_directory_uri() . '/js/search.js',
        ['jquery'],
        '1.0',
        true
    );

    // Ajax URLをJavaScriptに渡す
    wp_localize_script(
        'search-script',
        'live_tax_vars',
        [
            'ajax_url' => admin_url('admin-ajax.php')
        ]
    );

    // main.jsを最後に読み込む
    wp_enqueue_script(
        'main_script',
        get_template_directory_uri() . '/js/main.js',
        ['jquery'],
        '1.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'my_theme_assets');

// サムネイル設定を有効化
add_theme_support('post-thumbnails');


// タクソノミー設定
add_action('init', function () {
    $common = [
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
    ];

    register_taxonomy('site_type', ['post'], array_merge($common, [
        'labels' => ['name' => '作品タイプ'],
        'hierarchical' => true,
        'rewrite' => ['slug' => 'site-type', 'with_front' => false],
    ]));

    register_taxonomy('design_type', ['post'], array_merge($common, [
        'labels' => ['name' => 'デザイン'],
        'hierarchical' => false,
        'rewrite' => ['slug' => 'design', 'with_front' => false],
    ]));

    register_taxonomy('color', ['post'], array_merge($common, [
        'labels' => ['name' => 'カラー'],
        'hierarchical' => false,
        'rewrite' => ['slug' => 'color', 'with_front' => false],
    ]));

    register_taxonomy('tech_stack', ['post'], array_merge($common, [
        'labels' => ['name' => '使用ツール'],
        'hierarchical' => false,
        'rewrite' => ['slug' => 'tech', 'with_front' => false],
    ]));
});

// サーバ側でも「名前＆メール必須」を無効化（保険）
add_filter('pre_option_require_name_email', '__return_zero');
// （未ログインでも投稿可にしたい場合の保険）
add_filter('pre_option_comment_registration', '__return_zero');

// コメントにコメント用の comment-reply.js を必要時のみ読み込み
add_action('wp_enqueue_scripts', function () {
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
});

// テーマ有効化時にパーマリンク設定を再生成
add_action('after_switch_theme', function () {
    flush_rewrite_rules();
});


// 検索パート

// Ajaxライブ検索用コールバック
add_action('wp_ajax_live_tax_search', 'live_tax_search_callback');
add_action('wp_ajax_nopriv_live_tax_search', 'live_tax_search_callback');



/**
 * 検索結果用の画像URLを取得する関数
 * 優先順位: 1. ACF 'hero_image' > 2. アイキャッチ画像 > 3. ダミー画像
 *
 * @param int $post_id 投稿ID。
 * @return string 画像URL。
 */
function get_search_result_image_url($post_id)
{
    $image_url = false;
    $image_size = 'medium'; // フロントページで使用しているサイズに合わせる

    // 1. ACF: 'hero_image' フィールドから画像を取得
    if (function_exists('get_field')) {
        $hero_image = get_field('hero_image', $post_id);

        if ($hero_image) {
            // ACFフィールドの値が画像IDの場合 (推奨)
            if (is_numeric($hero_image)) {
                $image_array = wp_get_attachment_image_src((int)$hero_image, $image_size);
                $image_url = $image_array ? $image_array[0] : false;
            }
            // ACFフィールドの値が画像配列の場合
            elseif (is_array($hero_image) && ! empty($hero_image['ID'])) {
                $image_array = wp_get_attachment_image_src((int)$hero_image['ID'], $image_size);
                $image_url = $image_array ? $image_array[0] : false;
            }
            // ACFフィールドの値が画像URLの場合
            elseif (is_array($hero_image) && ! empty($hero_image['url'])) {
                $image_url = esc_url($hero_image['url']);
            }
        }
    }

    // 2. ACF画像が取得できなかった場合、アイキャッチ画像を取得
    if (! $image_url && has_post_thumbnail($post_id)) {
        $image_array = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $image_size);
        $image_url = $image_array ? $image_array[0] : false;
    }

    // 3. どちらもなかった場合、ダミー画像を設定
    if (! $image_url) {
        // フロントページで使用しているダミー画像のパスに置き換えてください
        $image_url = get_template_directory_uri() . '/img/cards/dummy-300X200.png';
    }

    return $image_url;
}

/**
 * Ajax ライブ検索用コールバック
 */
function live_tax_search_callback()
{
    // ===== 1. キーワードを取得 =====
    $keyword = sanitize_text_field($_POST['keyword'] ?? '');
    if (empty($keyword)) {
        wp_send_json([]);
    }

    $results  = [];
    $post_ids = [];

    // ===== 2. 投稿タイトル・本文・ACF overview を検索 =====
    $post_query_args = [
        'post_type'      => 'post',
        's'              => $keyword,
        'posts_per_page' => -1, // 全件取得
    ];
    $post_query = new WP_Query($post_query_args);

    if ($post_query->have_posts()) {
        while ($post_query->have_posts()) {
            $post_query->the_post();
            $post_id = get_the_ID();

            $text = '';
            if (function_exists('get_field')) {
                // 投稿IDを指定して取得
                $text = get_field('overview', $post_id);

                // それでも空なら get_post_meta で直接取得してみる
                if (empty($text)) {
                    $text = get_post_meta($post_id, 'overview', true);
                }
            }

            // 最終フォールバックは本文
            if (empty($text)) {
                $content = get_the_content();
                $text = wp_strip_all_tags(strip_shortcodes($content));
            }

            // 配列に格納
            $results[] = [
                'title'        => get_the_title(),
                'link'         => get_permalink(),
                'excerpt'      => wp_trim_words($excerpt_source, 20, '…'),
                'overview_raw' => $text, // ← NetworkでHTML付きのoverview確認用
                'thumbnail'    => get_search_result_image_url($post_id),
            ];

            $post_ids[] = $post_id;
        }
    }
    wp_reset_postdata();

    // ===== 3. タクソノミー（カテゴリ・タグなど）も検索 =====
    $taxonomies_to_search = ['category', 'site_type', 'post_tag', 'design_type', 'color', 'tech_stack'];
    $matching_terms = get_terms([
        'taxonomy'   => $taxonomies_to_search,
        'name__like' => $keyword,
        'hide_empty' => false,
    ]);

    if (!empty($matching_terms) && !is_wp_error($matching_terms)) {
        foreach ($matching_terms as $term) {
            $term_posts_query_args = [
                'post_type'      => 'post',
                'posts_per_page' => -1,
                'tax_query'      => [
                    [
                        'taxonomy' => $term->taxonomy,
                        'field'    => 'slug',
                        'terms'    => $term->slug,
                    ],
                ],
            ];
            $term_posts_query = new WP_Query($term_posts_query_args);

            if ($term_posts_query->have_posts()) {
                while ($term_posts_query->have_posts()) {
                    $term_posts_query->the_post();
                    $post_id = get_the_ID();

                    if (function_exists('get_field')) {
                        // 投稿IDを指定して取得
                        $text = get_field('overview', $post_id);

                        // それでも空なら get_post_meta で直接取得してみる
                        if (empty($text)) {
                            $text = get_post_meta($post_id, 'overview', true);
                        }
                    }

                    // 最終フォールバックは本文
                    if (empty($text)) {
                        $content = get_the_content();
                        $text = wp_strip_all_tags(strip_shortcodes($content));
                    }

                    // 投稿IDの重複防止
                    if (!in_array($post_id, $post_ids)) {
                        $results[] = [
                            'title'     => get_the_title(),
                            'link'      => get_permalink(),
                            // 'excerpt'   => wp_trim_words(get_the_excerpt(), 20, '…'),
                            'excerpt'      => wp_trim_words($excerpt_source, 20, '…'),
                            'overview_raw' => $text, // ← NetworkでHTML付きのoverview確認用
                            'thumbnail' => get_search_result_image_url($post_id),
                        ];
                        $post_ids[] = $post_id;
                    }
                }
            }
            wp_reset_postdata();
        }
    }

    // ===== 4. JSONで出力 =====
    wp_send_json($results);
}

// 以前のAjaxコールバック名が「sf_live_search」だった場合はこちらも有効にする
add_action('wp_ajax_sf_live_search', 'live_tax_search_callback');
add_action('wp_ajax_nopriv_sf_live_search', 'live_tax_search_callback');


// CPT UI 「eventpost」
function create_eventpost_type()
{
    register_post_type(
        'eventpost',
        array(
            'labels' => array(
                'name'          => 'イベント',
                'singular_name' => 'イベント'
            ),
            'public'        => true,
            'has_archive'   => true,
            'menu_icon'     => 'dashicons-calendar-alt',
            'supports'      => array('title', 'editor', 'thumbnail')
        )
    );
}
add_action('init', 'create_eventpost_type');
