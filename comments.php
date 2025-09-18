<?php

/**
 * comments.php
 * コメント表示＆フォーム
 */

// コメントが閉じられていて、コメントも無い場合は終了
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if (have_comments()) : ?>
        <h2 class="comments-title">コメント</h2>

        <ul class="comment-list">
            <?php
            wp_list_comments(array(
                'style'      => 'ul',
                'short_ping' => true,
                'avatar_size' => 0, // アバター不要
                'callback'   => function ($comment, $args, $depth) {
            ?>
                <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
                    <p><strong><?php comment_author(); ?></strong> — <br>
                        <time datetime="<?php comment_time('c'); ?>">
                            <?php comment_date('Y年n月j日 H:i'); ?>
                        </time>
                    </p>
                    <div class="comment-content">
                        <?php comment_text(); ?>
                    </div>
                </li>
            <?php
                }
            ));
            ?>
        </ul>

    <?php endif; ?>

    <?php
    // コメントフォームのカスタマイズ
    $commenter = wp_get_current_commenter();
    $req       = get_option('require_name_email');
    $aria_req  = $req ? " aria-required='true' required" : '';

    $fields = array(
        // ニックネーム（先に表示）
        'author'  => '<p class="comment-form-author">
                <label for="author">ニックネーム <span class="required">*</span></label>
                <input id="author" name="author" type="text"
                        value="' . esc_attr($commenter['comment_author']) . '"
                        size="30"' . $aria_req . ' />
                </p>',
        // 「次回のコメントで…」チェックボックスを消す
        'cookies' => ''
    );

    comment_form(array(
        'title_reply'          => '',          // 「コメントする」を非表示
        'comment_notes_before' => '',          // 上の注意書きなし
        'comment_notes_after'  => '',          // 下の注意書きなし
        'label_submit'         => 'コメントを送信',
        'fields'               => $fields,     // ← author と cookies だけ（email/url を出さない）
        // コメント本文（ニックネームの後に表示）
        'comment_field'        => '<p class="comment-form-comment">
                            <label for="comment">コメント <span class="required">*</span></label>
                            <textarea id="comment" name="comment" cols="45" rows="5" aria-required="true" required></textarea>
                            </p>',
        'logged_in_as'         => ''           // ログインメッセージを出さない
    ));
    ?>
</div>