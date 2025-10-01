<?php
if (post_password_required()) return;
?>

<div id="comments" class="comments-area">

    <?php if (have_comments()) : ?>
        <h2 class="comments-title">コメント</h2>

        <ul class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ul',
                'short_ping'  => true,
                'avatar_size' => 0,
                'type'        => 'comment',
                'max_depth'   => get_option('thread_comments_depth', 5),
                'callback'    => function ($comment, $args, $depth) {
                    $child_count = get_comments(array(
                        'post_id' => $comment->comment_post_ID,
                        'parent'  => $comment->comment_ID,
                        'count'   => true,
                        'status'  => 'approve',
                    ));
            ?>
                <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
                    <p>
                        <strong><?php comment_author(); ?></strong> — <br>
                        <time datetime="<?php comment_time('c'); ?>"><?php comment_date('Y年n月j日 H:i'); ?></time>
                    </p>

                    <div class="comment-content">
                        <?php comment_text(); ?>
                    </div>

                    <div class="comment-actions">
                        <?php
                        // 返信リンク（入れ子）
                        comment_reply_link(array_merge($args, array(
                            'reply_text' => 'このコメントに返信',
                            'depth'      => $depth,
                            'max_depth'  => $args['max_depth'],
                        )));

                        // 子があるときだけ「返信を表示」
                        if ($child_count) {
                            echo '<button type="button" class="toggle-replies" aria-expanded="false" data-count="' . intval($child_count) . '">'
                            . '返信を表示（' . intval($child_count) . '）'
                            . '</button>';
                        }
                        ?>
                    </div>
                <?php
                },
                // ★ 閉じタグは end-callback に任せる
                'end-callback' => function () {
                    echo '</li>';
                },
            ));
                ?>
        </ul>
    <?php endif; ?>

    <?php
    // ===== フォーム：ニックネーム必須・メール/URLは出さない =====
    $commenter = wp_get_current_commenter();

    $fields = array(
        'author'  => '<p class="comment-form-author">
        <label for="author">ニックネーム <span class="required">*</span></label>
        <input id="author" name="author" type="text"
        value="' . esc_attr($commenter['comment_author']) . '"
        size="30" aria-required="true" required />
    </p>',
        'cookies' => ''
    );

    comment_form(array(
        'title_reply'          => '',
        'comment_notes_before' => '',
        'comment_notes_after'  => '',
        'logged_in_as'         => '',
        'label_submit'         => 'コメントを送信',
        'fields'               => $fields, // author のみ（email/url なし）
        'comment_field'        => '<p class="comment-form-comment">
        <label for="comment">コメント <span class="required">*</span></label>
        <textarea id="comment" name="comment" cols="45" rows="5" aria-required="true" required></textarea>
    </p>',
    ));
    ?>
</div>