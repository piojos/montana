<?php get_header(); ?>

<section id="content" role="main">
	<h2><strong>Sorry guys, this is Index.</strong></h2>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div class="">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</div>
	<?php endwhile; endif; ?>
</section>

<?php get_footer(); ?>
