<?php

	get_header();
	if(have_posts()) : while (have_posts()) : the_post(); ?>

<section id="content" role="main" class="page">
	<div class="header">

		<div class="bg_featured_banner" style="background-image:url(<?php the_post_thumbnail_url('huge'); ?>)"></div>

		<div class="max_wrap post_head loading">
			<div class="post_info main_content_column">
				<p class="label"><?php echo get_post_type(); ?></p>
				<h1><?php the_title(); ?></h1>
				<?php if(get_field('kicker')) echo '<p class="subtitle">'.get_field('kicker').'</p>'; ?>

				<div class="excerpt">
					<?php the_excerpt(); ?>
				</div>

				<div class="status_label">
					<?php get_template_part('inc/sharer'); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="post_body <?php if(!is_singular('colecciones')) echo 'max_wrap'; ?>">
		<div class="main_content_column">
			<div class="widget <?php echo $class; ?>">
				<div class="body_content">
					<?php the_content(); ?>
				</div>
			</div
		</div>
	</div>
</section>

<?php

	endwhile; endif;
	get_footer(); ?>
