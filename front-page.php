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
                    <a href="<?php comments_link(); ?>" class="comment">CM</a>
                    <a href="#" class="like">like</a>
                    <a href="<?php the_permalink(); ?>" class="view">view</a>
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
