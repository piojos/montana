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
				<p class="label"><?php
				$pt = get_post_type();
				echo keyword_gen($pt, true);
				$coll = get_field('collection_picker');
				if( $coll ) {
					$post = $coll;
					setup_postdata( $post );
							echo ' • <a href="'.get_the_permalink().'">'.get_the_title().'</a>';
					wp_reset_postdata();
				} ?>
				</p><?php

				logo_or_title();
				if(get_field('kicker')) echo '<p class="subtitle">'.get_field('kicker').'</p>';

				if(is_singular('cineteca')) {
					if(have_rows('meta')) { while (have_rows('meta')) { the_row();
						$og_title = get_sub_field('original_title');
						$mCast = get_sub_field('cast');
						if($og_title) {
							echo '<p class="subtitle">'.$og_title.'</p>';
						}
					}}
				} ?>

				<div class="excerpt"><?php
					if(is_singular('cineteca')) {
						$image = get_field('poster_img');
						if( !empty($image) ){ ?>
							<img src="<?php echo $image['sizes']['poster']; ?>" class="poster" alt="<?php echo $image['alt']; ?>" /><?php
						}
						echo movie_meta(false).'<br>';
						if($mCast) {
							echo '<p><strong>REPARTO</strong><p><p>'.$mCast.'</p><br>';
						}
					}
					if(is_singular('convocatorias')) {
						the_field('override_excerpt');
					} else {
						the_content();
					}
					if(is_singular('colecciones')) {
						echo '<br>'.mnt_card_status_label();
					} ?>
				</div>

				<div class="status_label">
					<?php get_template_part('inc/sharer'); ?>
				</div>
			</div><?php
			if(is_singular(array('agenda', 'cineteca', 'talleres', 'exposiciones', 'convocatorias'))) {
				get_template_part('inc/single', 'metabox');
			} ?>
		</div>
	</div>

	<div class="post_body <?php if(!is_singular('colecciones')) echo 'max_wrap'; ?>"><?php

		if(is_singular('colecciones')) {

			get_template_part('inc/single', 'coleccion');

		} else { ?>

		<div class="main_content_column"><?php
			if(is_singular('convocatorias')) { ?>
				<div class="body_content format">
					<?php the_content(); ?>
				</div><?php
			}
			get_template_part('inc/widgets');

			// Test comments
			if(get_current_user_id() == 1) {
				comments_template();
			} ?>
		</div><?php
		}


		if(is_singular('cineteca')) { ?>
			<div class="area max_wrap">
				<h2 class="area_title">Otras fechas y horarios para <?php the_title(); ?></h2>
				<div class="deck"><?php
					$schedArray = mta_future_schedule_array('Ymd');
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
		}


		get_template_part('inc/single', 'more'); ?>

	</div>
</section>

<?php

	endwhile; endif;
	get_footer(); ?>
