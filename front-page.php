<?php get_header(); ?>
<?php get_sidebar(); ?>

<main>
  <div class="wrapper info-top">
    <p class="info-details">
      みんなのオリジナルサイトが貼ってあります。<br />
      あなただけのオリジナルサイトを作る際の参考にしてね<br />
      いい作品にはいいねとコメントをしてあげましょう！
    </p>
    <p class="info-underline"></p>
  </div>

  <div class="wrapper" id="front-page">
    <ul class="cards-list">
    <?php 

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $the_query = new WP_Query( array(
            'post_status'    => 'publish',
            'post_type'      => 'post', // ページの種類（例、page、post、カスタム投稿タイプ）
            'paged'          => $paged,
            'posts_per_page' => 9, // 表示件数
            'orderby'        => 'date',
            'order'          => 'DESC'
        ) );
?>

<?php if ( $the_query->have_posts() ) : ?>
  <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
          <li class="card-container">
            <div class="card">
              <div class="card-image-box">

                <a href="<?php the_permalink(); ?>">
                  <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'medium', array( 'class' => 'card-image' ) ); ?>
                    <?php else: ?>
                      <!-- 画像ダミー -->
                      <img src="<?php echo esc_url(get_template_directory_uri() . '/img/cards/dummy-300X200.png' ); ?>" alt="<?php the_title(); ?>">
                  <?php endif; ?>
                </a>
                </div>
            

              <div class="card-body">
                <div class="card-top">
                  <a href="<?php the_permalink(); ?>">
                    <h3 class="card-title"><?php the_title(); ?></h3>
                  </a>
                  <!-- 表示するタグの数を調整 -->
                  <ul class="card-tags">
                    <?php
                  

                      // WP管理画面んで作成したタグとカテゴリー
                      $categories = get_the_category();
                      $originalTags = get_the_tags();
                      if(!empty($originalTags)){
                        foreach ( $originalTags as $tag ) {
                        echo '<li><p class="card-tag-list">' . esc_html( $tag->name ) . '</p></li>';
                        }
                      }
                      if ( !empty( $categories ) ) {
                        $count = count($categories);
                        $i = 0;
                         foreach ( $categories as $cat ) {
                           if(strtolower($cat-> name) === "uncategorized"){
                            continue;
                           }
                           if($i < 2){
                             echo '<li><p class="card-tag-list">' . esc_html( $cat->name ) . '</p></li>';
                           } 
                           $i++;
                         }
                         if($i >= 3){
                           echo '<p class="card-tag-list">...</p>';
                         }
                       } 

                      //NOTE： CSS上タグを多く出せないのでいっそのことなしにする？
                      // //タクソノミーで作成しているカテゴリーとタグ等
                      // $post_id = get_the_ID();

                      // // site_type
                      // $site_types = get_the_terms($post_id, 'site_type');
                      // if ( !empty($site_types) && !is_wp_error($site_types) ) {
                      //     foreach ($site_types as $term) {
                      //         echo '<li><p class="card-tag-list">' . esc_html($term->name) . '</p></li>';
                      //     }
                      // }

                      // // design_type
                      // $design_types = get_the_terms($post_id, 'design_type');
                      // if ( !empty($design_types) && !is_wp_error($design_types) ) {
                      //     foreach ($design_types as $term) {
                      //         echo '<li><p class="card-tag-list">' . esc_html($term->name) . '</p></li>';
                      //     }
                      // }

                      // // color
                      // $colors = get_the_terms($post_id, 'color');
                      // if ( !empty($colors) && !is_wp_error($colors) ) {
                      //     foreach ($colors as $term) {
                      //         echo '<li><p class="card-tag-list">' . esc_html($term->name) . '</p></li>';
                      //     }
                      // }

                      // // tech_stack
                      // $techs = get_the_terms($post_id, 'tech_stack');
                      // if ( !empty($techs) && !is_wp_error($techs) ) {
                      //     foreach ($techs as $term) {
                      //         echo '<li><p class="card-tag-list">' . esc_html($term->name) . '</p></li>';
                      //     }
                      // }
              
                      
                    ?>
                  </ul>
                </div>
                <p class="card-text"><?php echo get_the_excerpt(); ?></p>
              </div>

              <div class="card-bottom">
                  <p class="post-date"><?php echo get_the_date('Y/m/d'); ?></p>
                  <div class="card-icons">
              
                    <!-- いいねボタン -->
                        <?php if ( function_exists( 'wp_ulike' ) ) { wp_ulike(); } ?>
                    <!-- Clickボタン -->
                    <div class=post-view>
                       <a href="<?php the_permalink(); ?>" class="view">
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1" width="32" height="32" color="#4a2b00">
                              <defs><style>.cls-637b8170f95e86b59c57a03a-1{fill:none;stroke:currentColor;stroke-miterlimit:10;}</style></defs>
                              <g id="eye"><path class="cls-637b8170f95e86b59c57a03a-1" d="M22.5,12A12.24,12.24,0,0,1,12,17.73,12.24,12.24,0,0,1,1.5,12,12.24,12.24,0,0,1,12,6.27,12.24,12.24,0,0,1,22.5,12Z">
                              </path><circle class="cls-637b8170f95e86b59c57a03a-1" cx="12" cy="12" r="5.73"></circle>
                              <circle class="cls-637b8170f95e86b59c57a03a-1" cx="12" cy="12" r="1.91">
                              </circle></g>
                          </svg>
                          </a> 
                          <div style="display: inline-block;"><?php if(function_exists('the_views')) { the_views(); } ?></div>
                          
                    </div>
                    </div>
                </div>
            </div>
          </li>
        <?php endwhile; ?>

  <?php wp_reset_postdata(); ?>
      <?php else: ?>
        <p>投稿データがありませんでした</p>
        
      <?php endif; ?>

    </ul>
  <!-- wp-pagenaviの記述 -->
  <?php
the_posts_pagination( array(
  'mid_size' => 5,
  'prev_next' => true,
  'prev-text' => '&raquo;',
  'next-text' =>'&raquo;' ,
  'type' => 'list',
) );
?>
  <!-- ここまで -->
  </div>
</main>

<?php get_footer(); ?>
