<?php
get_header(); 
get_sidebar(); 
?>

<main class="page-content">


  <div class="wrapper info-top">
    <p class="info-details">
    検索結果<br />
    検索結果<br />
    検索結果
    </p>
    <p class="info-underline"></p>
  </div>

  <div class="wrapper">
    <ul class="cards-list">

    <?php
      $query = isset($_GET['query']) ? sanitize_text_field($_GET['query']) : '';
      
      if ( !empty($query) ) {

        $posts = [];

        // 投稿タイトル/本文検索
        $post_args = [
            'post_type' => 'post',
            's' => $query,
            'posts_per_page' => 10,
        ];
        $post_query = new WP_Query($post_args);
        if ( $post_query->have_posts() ) {
            $posts = array_merge($posts, $post_query->posts);
        }
        wp_reset_postdata();

        // カテゴリー名検索
        $cat = get_term_by('name', $query, 'category');
        if ($cat) {
            $cat_posts = get_posts([
                'post_type' => 'post',
                'posts_per_page' => 10,
                'tax_query' => [
                    [
                        'taxonomy' => 'category',
                        'field' => 'term_id',
                        'terms' => $cat->term_id,
                    ],
                ],
            ]);
            $posts = array_merge($posts, $cat_posts);
        }

        // タグ名検索
        $tag = get_term_by('name', $query, 'post_tag');
        if ($tag) {
            $tag_posts = get_posts([
                'post_type' => 'post',
                'posts_per_page' => 10,
                'tax_query' => [
                    [
                        'taxonomy' => 'post_tag',
                        'field' => 'term_id',
                        'terms' => $tag->term_id,
                    ],
                ],
            ]);
            $posts = array_merge($posts, $tag_posts);
        }

        // 重複削除
        $posts = array_unique($posts, SORT_REGULAR);

        if (!empty($posts)) {
            foreach ($posts as $post) {
                setup_postdata($post);
                ?>
            <li class="card-container">
                <div class="card">  
                    <a href="<?php echo esc_url(home_url('/details.html'));?>">
                        <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium', array( 'class' => 'card-image' ) ); ?>
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
                            if ( !empty( $categories ) ) {
                                foreach ( $categories as $cat ) {
                                echo '<li><p class="card-tag-list">' . esc_html( $cat->name ) . '</p></li>';
                                }
                            }
                            ?>
                        </ul>
                        </div>
                        <p class="card-text"><?php echo get_the_excerpt(); ?></p>

                        <div class="card-bottom">
                            <p class="post-date"><?php echo get_the_date('Y/m/d'); ?></p>
                            <div class="card-icons">
                                <!-- いいねボタン -->
                                    <?php if ( function_exists( 'wp_ulike' ) ) { wp_ulike(); } ?>
                                <!-- Clickボタン -->
                                <div class=post-view>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" width="32" height="32" color="#4a2b00">
                                        <defs><style>.cls-637b8170f95e86b59c57a03a-1{fill:none;stroke:currentColor;stroke-miterlimit:10;}</style></defs>
                                        <g id="eye"><path class="cls-637b8170f95e86b59c57a03a-1" d="M22.5,12A12.24,12.24,0,0,1,12,17.73,12.24,12.24,0,0,1,1.5,12,12.24,12.24,0,0,1,12,6.27,12.24,12.24,0,0,1,22.5,12Z">
                                        </path><circle class="cls-637b8170f95e86b59c57a03a-1" cx="12" cy="12" r="5.73"></circle>
                                        <circle class="cls-637b8170f95e86b59c57a03a-1" cx="12" cy="12" r="1.91">
                                        </circle></g>
                                    </svg>
                                    <a href="<?php the_permalink(); ?>" class="view"><?php the_views(false); ?></a> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        <?php
}

           
            wp_reset_postdata();
        } else {
            echo '<p>検索結果が見当たりませんでした</p>';
        }

      } else {
        echo '<p>検索ワードを入力してください</p>';
      }
    ?>
    </ul>

  </div>
</main>

<?php get_footer(); ?>
