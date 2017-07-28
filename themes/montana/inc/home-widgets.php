<?php

// filter
function my_posts_where( $where ) {
	$where = str_replace("meta_key = 'range_date_picker_%", "meta_key LIKE 'range_date_picker_%", $where);
	return $where;
}
add_filter('posts_where', 'my_posts_where');


function mta_override_title($fb = false){
	$or_t = get_sub_field('or_title');
	if($or_t) { $or_t = get_sub_field('or_title'); }
	elseif($fb) { $or_t = $fb; }
	return $or_t;
}


if( have_rows('bloques_principales') ): while ( have_rows('bloques_principales') ) : the_row();

// #queHacerHoy
	if( get_row_layout() == 'op_today_events' ):
	// jQuery: contar cards y distribuír de acuerdo al número.

		$or_title = mta_override_title();
		$today = current_time('Ymd');
		$selToday = get_sub_field('or_today');
		if($selToday) {
			$count = count($selToday);
			if($count <= 4) {
				if($count <= 2) {
					$class = ' twos';
				} else {
					$class = ' fours';
				}
			} else {
				$class = ' sixs';
			} ?>
		<div class="area max_wrap for_today special">
			<?php if($or_title) echo '<h2 class="area_title">'.$or_title.'</h2>'; ?>
			<?php slider_deck($selToday, $class, '', true); ?>
		</div><?php

		} else {
			$args = array(
				'post_type' => array('agenda', 'exposiciones', 'talleres'),
				'posts_per_page' => 3,
				'meta_query' => array(
					array(
						'key' => 'everyday',
						'value' => $today,
						'compare' => 'LIKE',
					),
				)
			);
			$qu = new WP_Query( $args );
			$count = $qu->post_count;
			if($count <= 4) {
				if($count <= 2) {
					$class = ' twos';
				} else {
					$class = ' fours';
				}
			} else {
				$class = ' sixs';
			} ?>
		<div class="area max_wrap for_today special">
			<?php if($or_title) echo '<h2 class="area_title">'.$or_title.'</h2>'; ?>
			<?php slider_deck($args, $class, ''); ?>
		</div><?php
		}





// Cineteca
	elseif( get_row_layout() == 'op_today_movies' ):

		$selMovies = get_sub_field('or_movies');
		if($selMovies) {
			$otm_titles = 'Hoy en Cineteca';
			$args = array(
				'post_type' => 'cineteca',
				'post__in' => $selMovies
			);
		} else {
			$otm_titles = 'En Cineteca';
			$args = array(
				'post_type' => 'cineteca',
				'posts_per_page' => 4,
				'meta_query' => array (
					array(
						'key' => 'everyday',
						'value' => $today,
						'compare' => 'LIKE',
					),
				)
			);
		}
		$otm_titles = mta_override_title(); ?>
		<div class="area max_wrap">
			<?php if($otm_titles) echo '<h2 class="area_title">'.$otm_titles.'</h2>'; ?>
			<?php slider_deck($args, 'fours movie'); ?>
		</div><?php




// esta semana
	elseif( get_row_layout() == 'op_week_events' ):

		$or_title = mta_override_title();

		$wd0 = date("Ymd", strtotime('today'));
		$wd1 = date("Ymd", strtotime('+1 day'));
		$wd2 = date("Ymd", strtotime('+2 day'));
		$wd3 = date("Ymd", strtotime('+3 day'));
		$wd4 = date("Ymd", strtotime('+4 day'));
		$wd5 = date("Ymd", strtotime('+5 day'));
		$wd6 = date("Ymd", strtotime('+6 day'));
		$wd7 = date("Ymd", strtotime('+7 day')); ?>

		<div class="area max_wrap "><?php

			if($or_title) echo '<h2 class="area_title">'.$or_title.'</h2>';

			$ftd_post = get_sub_field('featured');
			$deckClass = 'this_week';

			if($ftd_post) {
				$deckClass .= ' has_ftd_post';
				foreach ($ftd_post as $post) {
					setup_postdata($post);
					$exclude_ftd_post = $post->ID;
					echo '<div class="row threes ftd_row">';
					card('threes week_ftd_post', $post);
					echo '</div>';
				}
				wp_reset_postdata();
			}
			$exclude_ftd_post = array($exclude_ftd_post);

			$args = array(
				'post_type' => array('agenda'),
				'posts_per_page' => 10,
				'post__not_in' => $exclude_ftd_post,
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
				'orderby' => 'rand',
			);

			// NOTA: Agregar aparte query de exposiciones y talleres y usar merge_array en funcion de slider_deck()

			slider_deck($args, 'sixs', $deckClass); ?>
		</div><?php




// colecciones
	elseif( get_row_layout() == 'op_collections' ):

		$or_title = mta_override_title();
		$post_objects = get_sub_field('collections');

		if( $post_objects ): ?>
		<div class="area max_wrap collections" style="margin-bottom: 6em;">
			<?php if($or_title) echo '<h2 class="area_title">'.$or_title.'</h2>'; ?>
			<div class="controls">
				<ul><?php
				foreach( $post_objects as $post):
					setup_postdata($post); ?>
					<li><a href="#slide_<?php the_ID(); ?>" class="trig"><?php the_title(); ?></a></li><?php
				endforeach;
				wp_reset_postdata(); ?>
				</ul>
			</div><?php

			foreach( $post_objects as $post):
				setup_postdata($post); ?>
				<div class="details_container" id="slide_<?php the_ID(); ?>">
					<a class="close"></a>
					<div class="info column">
						<a href="<?php the_permalink(); ?>">
							<h1><?php the_title(); ?></h1>
							<?php if(get_field('kicker')) echo '<p class="subtitle">'.get_field('kicker').'</p>'; ?>
							<div class="excerpt">
								<?php the_content(); ?>
							</div>

							<div class="status_label">
								<?php echo '<br>'.mnt_card_status_label(); ?>
							</div>
						</a>
					</div>
					<div class="gallery column">
						<?php the_post_thumbnail('large'); ?>
					</div>
				</div><?php
			endforeach;
			wp_reset_postdata(); ?>
		</div><?php
		endif;



// conarteTV
	elseif( get_row_layout() == 'op_tv' ):

			if( have_rows('video_list') ): ?>
			<div class="area conarte_tv">
				<div class="max_wrap">
					<h2 class="area_title boxed">CON<strong>ARTE TV</strong></h2>
					<div class="deck slider_deck"><?php
				while (have_rows('video_list')) {
					the_row();
					echo '<div class="card tv fours">';
					if(get_sub_field('embed')) { ?>
						<div class="embed-container"><?php the_sub_field('embed'); ?></div><?php
					}
					echo '<h3>'.get_sub_field('title').'</h3><div class="about">'.get_sub_field('about').'</div></div>';
				} ?>
					</div>
				</div>
			</div><?php
			endif;


// Proximamente
	elseif( get_row_layout() == 'op_soon' ):

		$or_title = mta_override_title();
		$post_objects = get_sub_field('select_soon');

		if( $post_objects ): ?>
		<div class="area max_wrap">
			<?php if($or_title) echo '<h2 class="area_title">'.$or_title.'</h2>'; ?>
			<div class="deck slider_deck"><?php
				$count = getClassofQuery($post_objects);
				foreach( $post_objects as $post):
					setup_postdata($post);
					echo card($count);
				endforeach; ?>
			</div>
		</div><?php
		wp_reset_postdata();
		endif;


	endif;
endwhile; endif; ?>
