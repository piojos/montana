<?php

	// filter
	function my_posts_where( $where ) {
		$where = str_replace("meta_key = 'range_date_picker_%", "meta_key LIKE 'range_date_picker_%", $where);
		return $where;
	}
	add_filter('posts_where', 'my_posts_where');

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
			get_template_part('inc/widgets'); ?>
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




		// More...
		if(is_singular('colecciones')) {
			$args = array(
				'post_type' => get_post_type(),
				'posts_per_page' => 8,
				'orderby' => 'rand',
			);
		} else {
			$wd0 = date("Ymd", strtotime('today'));
			$wd1 = date("Ymd", strtotime('+1 day'));
			$wd2 = date("Ymd", strtotime('+2 day'));
			$wd3 = date("Ymd", strtotime('+3 day'));
			$wd4 = date("Ymd", strtotime('+4 day'));
			$wd5 = date("Ymd", strtotime('+5 day'));
			$wd6 = date("Ymd", strtotime('+6 day'));
			$wd7 = date("Ymd", strtotime('+7 day'));

			$args = array(
				'post_type' => get_post_type(),
				'posts_per_page' => 8,
				// 'post__not_in' => $exclude_ftd_post,
				'meta_query' => array(
					'relation' => 'OR',
					array('key' => 'everyday', 'value' => $wd0, 'compare' => 'LIKE',),
					array('key' => 'everyday', 'value' => $wd1, 'compare' => 'LIKE',),
					array('key' => 'everyday', 'value' => $wd2, 'compare' => 'LIKE',),
					array('key' => 'everyday', 'value' => $wd3, 'compare' => 'LIKE',),
					array('key' => 'everyday', 'value' => $wd4, 'compare' => 'LIKE',),
					array('key' => 'everyday', 'value' => $wd5, 'compare' => 'LIKE',),
					array('key' => 'everyday', 'value' => $wd6, 'compare' => 'LIKE',),
					array('key' => 'everyday', 'value' => $wd7, 'compare' => 'LIKE',),
					array(
						'key'		=> 'range_date_picker_%_start_day',
						'compare'	=> '<=',
						'value'		=> $wd0,
					),
					array(
						'key'		=> 'range_date_picker_%_end_day',
						'compare'	=> '>=',
						'value'		=> $wd7,
					)
				),
				// 'orderby' => 'rand',
			);
		}

		if($args) { ?>
			<div class="area max_wrap">
				<h2 class="area_title">Más en <?php echo get_post_type(); ?></h2>
				<?php slider_deck($args, 'fours'); ?>
			</div><?php
		} ?>

	</div>
</section>

<?php

	endwhile; endif;
	get_footer(); ?>
