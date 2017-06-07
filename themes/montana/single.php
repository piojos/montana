<?php

	get_header();
	if(have_posts()) : while (have_posts()) : the_post(); ?>

<section id="content" role="main" class="single">
	<div class="header"><?php

		if(has_post_thumbnail()) { ?>
			<div class="bg_featured_banner" style="background-image:url(<?php the_post_thumbnail_url('huge'); ?>)"></div><?php
		} else { ?>
			<div class="bg_featured_banner no_image"></div><?php
		} ?>
		<div class="max_wrap post_head loading">
			<div class="post_info main_content_column">
				<p class="label"><?php $pt = get_post_type(); echo keyword_gen($pt, true); ?></p>

				<?php logo_or_title(); ?>
				<?php if(get_field('kicker')) echo '<p class="subtitle">'.get_field('kicker').'</p>'; ?>

				<div class="excerpt"><?php
					if(is_singular('cineteca')) {
						$image = get_field('poster_img');
						if( !empty($image) ){ ?>
							<img src="<?php echo $image['sizes']['poster']; ?>" class="poster" alt="<?php echo $image['alt']; ?>" /><?php
						}
						echo movie_meta(false);
					}
					the_content(); ?>
				</div>

				<div class="status_label">
					<?php get_template_part('inc/sharer'); ?>
				</div>
			</div><?php
			if(is_singular(array('agenda', 'cineteca', 'talleres', 'exposiciones'))) {
				get_template_part('inc/single', 'metabox');
			} ?>
		</div>
	</div>

	<div class="post_body <?php if(!is_singular('colecciones')) echo 'max_wrap'; ?>"><?php

		if(is_singular('colecciones')) {

			if(have_rows('event_days')) {
				while (have_rows('event_days')) {
					the_row(); ?>

					<ul class="ag_results">
						<li>
							<div class="max_wrap">
								<h3> <?php
								$day = get_sub_field('day');
								$newDate = date_i18n('l F d Y', strtotime($day));
								echo prefix_forDay($day, '<span>', '</span>').$newDate;
								 ?></h3>
							</div><?php

							$post_objects = get_sub_field('events');
							if( $post_objects ) { ?>
							<ul><?php
								foreach( $post_objects as $post) {
									setup_postdata($post);
									list_card();
								} ?>
							</ul><?php
							wp_reset_postdata();
							} ?>
						</li>
					</ul><?php
				}
			}
		} else { ?>

		<div class="main_content_column"><?php
			get_template_part('inc/widgets'); ?>
		</div><?php
		}


		if(is_singular('cineteca')) { ?>
			<div class="area max_wrap">
				<h2 class="area_title">Otras fechas y horarios para <?php the_title(); ?></h2>
				<div class="deck"><?php
					$schedArray = movieFutureSchedule_array('Ymd');
					if($schedArray) {
						foreach ($schedArray as $key) {
							$niceday = date_i18n('l F d', strtotime($key[0]));
							echo '<div class="schedule card fours"><div class="details box"><div class="wrap">';
							echo prefix_forDay($key[0]);
							echo $niceday.'<br><ul class="hours">';
							if(is_array($key[1])){ foreach ($key[1] as $hour) {
								echo '<li>'.$hour.'</li>';
							}}
							echo '</ul></div></div></div>';
						}
					} else {
						echo '<div class="schedule card fours"><div class="details box"><div class="wrap"> Esta película no se ha programado nuevamente en cartelera.</div></div></div>';
					} ?>
				</div>
			</div><?php
		} ?>

		<div class="area max_wrap">
			<h2 class="area_title">Mas en <?php echo get_post_type(); ?></h2><?php
			$args = array(
				'post_type' => get_post_type(),
				'posts_per_page' => 4
			);
			deck($args, 'fours'); ?>
		</div>
	</div>
</section>

<?php

	endwhile; endif;
	get_footer(); ?>
