<?php

	get_header();
	if ( have_posts() ) : while ( have_posts() ) : the_post();

	$newsExcIDs = get_field('hide_news'); ?>



<section id="content" role="main" class="home"><?php

// slider

	$sliderPosts = get_field('featured_slider', 2);

	if($sliderPosts) { ?>
	<div class="big slider"><?php
		foreach( $sliderPosts as $post) {
			setup_postdata($post); ?>
			<div class="slide">
				<a href="<?php the_permalink(); ?>">
					<div class="bg_img" style="background-image:url(<?php the_post_thumbnail_url('huge'); ?>);"></div>
				</a>
				<div class="max_wrap">
					<div class="details box">
						<a href="<?php the_permalink(); ?>">
							<?php echo keyword_box(); ?>
							<?php logo_or_title('h2'); ?>
							<?php if(get_field('kicker')) echo '<p class="subtitle">'.get_field('kicker').'</p>'; ?>
							<div class="about excerpt">
								<?php the_content(); ?>
							</div>
						</a>
						<div class="status_label"><?php
							// <p><strong>Hasta Marzo 14</strong></p>
							// <p>Location Taxonomy</p>
							get_template_part('inc/sharer'); ?>
						</div>
					</div>
				</div>
			</div><?php
		} ?>
	</div><?php
	}








// Noticias

	$latestQuery = new WP_Query( array('post_type' => 'post', 'posts_per_page' => 1 ) );
	$restQuery = new WP_Query( array('post_type' => 'post', 'post__not_in' => $newsExcIDs, 'offset' => 1, 'posts_per_page' => 5) );


	if ( $latestQuery->have_posts() ) { ?>
	<div class="dropdown closed max_wrap">
		<div class="latest story"><?php
		while ( $latestQuery->have_posts() ) {
			$latestQuery->the_post(); ?>
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail(); ?>
				<div class="title">
					<div>
						<p class="label">Noticias</p>
						<h2><?php the_title(); ?></h2>
					</div>
				</div>
			</a><?php
		}
		wp_reset_postdata(); ?>
		<a class="toggle_button"></a>
		</div><?php
		if ( $restQuery->have_posts() ) { ?>
		<ul class="stories"><?php
			while ( $restQuery->have_posts() ) {
				$restQuery->the_post(); ?>
			<li>
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail(); ?>
					<div class="title"><div>
						<h2><?php the_title(); ?></h2>
					</div></div>
				</a>
			</li><?php
			} ?>
		</ul><?php
		wp_reset_postdata();
		} ?>
	</div><?php
	}

	get_template_part('inc/home', 'widgets'); ?>

</section>
<?php


	endwhile; endif;
	get_footer(); ?>
