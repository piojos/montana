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
			setup_postdata($post);
			$sl_ops = get_field('slider_options'); ?>
			<div class="slide">
				<a href="<?php the_permalink(); ?>">
					<div class="bg_img <?php if($sl_ops && in_array('blur_img', $sl_ops)) echo 'blur'; ?>" style="background-image:url(<?php the_post_thumbnail_url('huge'); ?>);"></div>
				</a>
				<div class="max_wrap">
					<div class="details box">
						<a href="<?php the_permalink(); ?>">
							<?php echo keyword_box();
							logo_or_title('h2');
							if($sl_ops && in_array('show_slogan', $sl_ops)) {
								if(get_field('kicker')) echo '<p class="subtitle">'.get_field('kicker').'</p>';
							}
							?>
							<div class="about excerpt">
								<?php
								if($sl_ops && in_array('show_excerpt', $sl_ops)) {
									if($sl_ops && in_array('show_location', $sl_ops) || $sl_ops && in_array('show_duration', $sl_ops)) {
										the_excerpt();
									} else {
										the_content();
									}
								}
								if($sl_ops && in_array('show_location', $sl_ops)) echo '<p>Lugar</p>';
								if($sl_ops && in_array('show_duration', $sl_ops)) echo '<p>Duración</p>'; ?>
							</div>
						</a>
						<div class="status_label">
							<?php get_template_part('inc/sharer'); ?>
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
