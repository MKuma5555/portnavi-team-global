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

  <div class="wrapper">
    <ul class="cards-list">

      <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
          <li class="card-container">
            <div class="card">
                      <a href="<?php the_permalink();?>">
                      <?php if ( has_post_thumbnail() ) : ?>
                  <?php the_post_thumbnail( 'medium', array( 'class' => 'card-image' ) ); ?>
                <?php endif; ?>
              </a>
              <!-- <a href="<?php echo esc_url(home_url('/details.html'));?>">
                <?php if ( has_post_thumbnail() ) : ?>
                  <?php the_post_thumbnail( 'medium', array( 'class' => 'card-image' ) ); ?>
                <?php endif; ?>
              </a> -->

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
        <?php endwhile; ?>
      <?php else: ?>
        <p>投稿データがありませんでした</p>
      <?php endif; ?>

    </ul>
  </div>
</main>

<?php get_footer(); ?>
