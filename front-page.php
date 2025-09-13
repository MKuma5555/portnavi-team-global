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
                    <!-- コメントボタン -->
                    <a href="<?php comments_link(); ?>" class="comment">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="_x32_" x="0px" y="0px" viewBox="0 0 512 512" style="width: 32px; height: 32px; opacity: 1;" xml:space="preserve">
                            <style type="text/css">.st0{fill:#4B4B4B;}</style>
                            <g><path class="st0" d="M443.245,152.171h-87.072v-42.546c-0.008-37.98-30.774-68.746-68.754-68.754H68.755   C30.774,40.879,0.008,71.644,0,109.625v163.01c0.008,37.581,30.146,68.053,67.581,68.697L55.98,399.333   c-1.353,6.774,1.565,13.63,7.378,17.348c5.821,3.717,13.264,3.481,18.84-0.587l102.227-74.706h27.236   c1.842,36.342,31.776,65.241,68.575,65.249h75.318l83.844,61.271c5.576,4.068,13.019,4.305,18.839,0.587   c5.812-3.717,8.731-10.573,7.378-17.348l-9.163-45.806c31.662-6.171,55.54-34.002,55.548-67.458V220.925   C511.992,182.953,481.234,152.179,443.245,152.171z M178.97,307.998c-3.57,0-6.97,1.108-9.847,3.212l-71.992,52.613l7.166-35.852   c0.987-4.916-0.286-9.986-3.456-13.859c-3.18-3.88-7.9-6.114-12.913-6.114H68.755c-9.816-0.008-18.554-3.93-25.011-10.361   c-6.424-6.449-10.345-15.188-10.353-25.002v-163.01c0.008-9.815,3.93-18.554,10.353-25.011   c6.457-6.424,15.195-10.344,25.011-10.353h218.664c9.814,0.008,18.554,3.929,25.002,10.353   c6.432,6.457,10.353,15.196,10.361,25.011v42.546h-42.546c-37.98,0.008-68.747,30.774-68.754,68.754v87.073H178.97z    M478.609,337.883c-0.008,9.823-3.929,18.554-10.354,25.011c-6.456,6.424-15.187,10.344-25.01,10.353h-6.896   c-5.014,0-9.734,2.234-12.913,6.114c-3.18,3.873-4.443,8.943-3.457,13.859l4.484,22.418l-53.608-39.178   c-2.878-2.104-6.278-3.212-9.848-3.212h-80.771c-9.815-0.008-18.554-3.929-25.011-10.361c-6.424-6.449-10.345-15.188-10.353-25.002   v-13.19V220.925c0.008-9.823,3.929-18.554,10.353-25.002c6.456-6.432,15.196-10.353,25.011-10.361h59.241h103.768   c9.824,0.008,18.554,3.929,25.01,10.353c6.425,6.457,10.346,15.188,10.354,25.011V337.883z" style="fill: rgb(74, 43, 0);"/></g>
                        </svg>
                    </a>
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
