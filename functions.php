<?php
add_action('wp_enqueue_scripts', 'add_styles');


function add_styles() {
    // 1. reset.css
    wp_register_style(
        'reset_style',
        'https://unpkg.com/ress/dist/ress.min.css',
        array(),
        '1.0'
    );
    wp_enqueue_style('reset_style');

    // 2. Google Fonts
    wp_enqueue_style(
        'google-fonts-monoton',
        'https://fonts.googleapis.com/css2?family=Monoton&display=swap',
        array(),
        null
    );

    // 3. main.css
    wp_enqueue_style(
        'main_style',
        get_template_directory_uri() . '/css/main.css',
        array('reset_style'),
        '1.0'
    );

    // 4. index.css（mainの後に読み込み）
    wp_enqueue_style(
        'index_style',
        get_template_directory_uri() . '/css/index.css',
        array('reset_style', 'main_style'),
        '1.0'
    );

    // 5. detail.css（mainの後に読み込み）
    wp_enqueue_style(
        'detail_style',
        get_template_directory_uri() . '/css/details.css',
        array('reset_style', 'main_style'),
        '1.0'
    );

    // 6. event.css（eventページだけ）
    if(is_page('event')){
        wp_enqueue_style(
            'event_style',
            get_template_directory_uri() . '/css/event.css',
            array('reset_style', 'main_style'),
            '1.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'add_styles');

function add_scripts()
{
    // デフォルトのjQueryを削除
    wp_deregister_script('jquery');


    // jQuery を CDN から読み込む
    wp_register_script(
        'jquery',
        'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js',
        array(),
        '3.6.0',
        true
    );

    // WordPress 標準 jQuery をそのまま使う
    wp_enqueue_script('jquery');

    // noConflict モードで jQuery を $ に割り当てない
    // wp_add_inline_script('jquery', 'jQuery.noConflict();');

    // search&filterのjs
    wp_enqueue_script(
        'search-script',
        get_template_directory_uri() . '/js/search.js',
        array('jquery'),
        '1.0',
        true
    );

    // main.jsを最後に実行
    wp_enqueue_script(
        'main_script',
        get_template_directory_uri() . '/js/main.js',
        array('jquery'),
        // 'jquery_script' , 'slick-script' が読み込まれた後に'main_script'を読み込む
        '1.0',
        true
    );
}


// サムネイル設定を有効化
add_theme_support('post-thumbnails');

// タグ付け用タクソノミー設計（サイトタイプ / デザインタイプ / カラー / 使用ツール）
add_action('init', function () {
    // 共通オプション
    $common = [
        'public'            => true,              // フロントでも使える（URL/アーカイブ可）
        'publicly_queryable' => true,
        'show_ui'           => true,              // 管理画面で編集可
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'      => true,              // ブロックエディタ対応
        'show_admin_column' => true,              // 投稿一覧にカラムを出す
        'query_var'         => true,              // ?site_type=corporate のようなクエリOK
    ];

    // Webサイトカテゴリ別（親子階層あり）
    register_taxonomy('site_type', ['post'], array_merge($common, [
        'labels'       => ['name' => 'Webサイトカテゴリ別'],
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'site-type', 'with_front' => false],
    ]));

    // デザインカテゴリ別（タグ的：親子なし）
    register_taxonomy('design_type', ['post'], array_merge($common, [
        'labels'       => ['name' => 'デザインカテゴリ別'],
        'hierarchical' => false,
        'rewrite'      => ['slug' => 'design', 'with_front' => false],
    ]));

    // カラー別（タグ的：親子なし）
    register_taxonomy('color', ['post'], array_merge($common, [
        'labels'       => ['name' => 'カラー別'],
        'hierarchical' => false,
        'rewrite'      => ['slug' => 'color', 'with_front' => false],
    ]));

    // 使用ツール（タグ的：親子なし）
    register_taxonomy('tech_stack', ['post'], array_merge($common, [
        'labels'       => ['name' => '使用ツール'],
        'hierarchical' => false,
        'rewrite'      => ['slug' => 'tech', 'with_front' => false],
    ]));
});

// テーマ有効化時にパーマリンク設定を再生成（404対策）
add_action('after_switch_theme', function () {
    flush_rewrite_rules();
});
