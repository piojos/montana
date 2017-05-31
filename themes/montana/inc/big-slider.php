<?php

	$sliderPosts = get_field('featured_list');

	if($sliderPosts) { ?>
	<div class="home slider"><?php
		foreach( $sliderPosts as $post) {
			setup_postdata($post); ?>
			<div class="slide">
				<a href="#">
					<div class="bg_img" style="background-image:url(<?php the_post_thumbnail_url('huge'); ?>);"></div>
				</a>
				<div class="max_wrap">
					<div class="details box">
						<a href="<?php the_permalink(); ?>">
							<span class="parent_label"><?php echo get_post_type(); ?></span>
							<h2><?php the_title(); ?></h2>
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
	wp_reset_postdata();
	}
?>
