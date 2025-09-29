

<?php get_header(); ?>
<?php get_sidebar(); ?>
<main class="page-content">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
      <h1 class="page-title"><?php the_title(); ?></h1>
    <p>確認</p>
      <div class="page-body">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>
