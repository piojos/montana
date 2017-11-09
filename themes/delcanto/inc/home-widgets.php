<?php

// filter
function my_posts_where( $where ) {
	$s = array("meta_key = 'dates_picker_%", "meta_key = 'range_date_picker_%");
	$r = array("meta_key LIKE 'dates_picker_%", "meta_key LIKE 'range_date_picker_%");
	$where = str_replace($s, $r, $where);
	return $where;
}
add_filter('posts_where', 'my_posts_where');


function mta_override_title($fb = false){
	$or_t = get_sub_field('or_title');
	if($or_t) { $or_t = get_sub_field('or_title'); }
	elseif($fb) { $or_t = $fb; }
	return $or_t;
}

$today = current_time('Ymd');
$wd1 = $today + 1;
$wd2 = $wd1 + 1;
$wd3 = $wd2 + 1;
$wd4 = $wd3 + 1;
$wd5 = $wd4 + 1;
$wd6 = $wd5 + 1;
$wd7 = $wd6 + 1;

if( have_rows('bloques_principales') ): while ( have_rows('bloques_principales') ) : the_row();

// #queHacerHoy
	if( get_row_layout() == 'op_today_events' ):
	// jQuery: contar cards y distribuír de acuerdo al número.

		$or_title = mta_override_title();
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
		<div class="area max_wrap for_today">
			<?php if($or_title) echo '<h2 class="area_title">'.$or_title.'</h2>'; ?>
			<?php slider_deck($selToday, $class, '', true); ?>
		</div><?php

		} else {
			$args = array(
				'post_type' => array('agenda', 'exposiciones', 'talleres'),
				'posts_per_page' => 12,
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => 'everyday',
						'value' => $today,
						'compare' => 'LIKE',
					)
				),
				'orderby' => 'rand'
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
		<div class="area max_wrap for_today">
			<?php if($or_title) echo '<h2 class="area_title">'.$or_title.'</h2>'; ?>
			<?php slider_deck($args, $class, ''); ?>
		</div><?php
		}





// Cineteca
	elseif( get_row_layout() == 'op_today_movies' ):

		$selMovies = get_sub_field('or_movies');
		if($selMovies) {
			$otm_titles = 'Hoy en Cartelera';
			$args = array(
				'post_type' => 'cineteca',
				'post__in' => $selMovies
			);
		} else {
			$otm_titles = 'En Cartelera';
			$args = array(
				'post_type'		=> 'cineteca',
				'meta_query'	=> array (
					'relation' 	=> 'OR',
					array(
						'key'       => 'everyday',
						'value'     => $wd1,
						'compare'   => 'LIKE',
					),
					array(
						'key'       => 'everyday',
						'value'     => $today,
						'compare'   => 'LIKE',
					)
				),
				'posts_per_page' 	=> 12,
				'meta_key'		=> 'dates_picker_0_day',
				'orderby' 		=> 'meta_value_num',
				'order' 		=> 'ASC'
			);
		}
		$otm_titles = mta_override_title(); ?>
		<div class="area max_wrap">
			<?php if($otm_titles) echo '<h2 class="area_title">'.$otm_titles.'</h2>'; ?>
			<?php slider_deck($args, 'fours movie'); ?>
		</div><?php




// esta semana
	elseif( get_row_layout() == 'op_week_events' ):

		$or_title = mta_override_title(); ?>

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
				'posts_per_page' => 12,
				'post__not_in' => $exclude_ftd_post,
				'meta_query' => array(
					'relation' => 'OR',
					array('key' => 'everyday', 'value' => $today, 'compare' => 'LIKE',),
					array('key' => 'everyday', 'value' => $wd1, 'compare' => 'LIKE',),
					array('key' => 'everyday', 'value' => $wd2, 'compare' => 'LIKE',),
					array('key' => 'everyday', 'value' => $wd3, 'compare' => 'LIKE',),
					array('key' => 'everyday', 'value' => $wd4, 'compare' => 'LIKE',),
					array('key' => 'everyday', 'value' => $wd5, 'compare' => 'LIKE',),
					array('key' => 'everyday', 'value' => $wd6, 'compare' => 'LIKE',),
					array('key' => 'everyday', 'value' => $wd7, 'compare' => 'LIKE',),
					array(
						'key'		=> 'range_date_picker_0_start_day',
						'compare'	=> '>=',
						'value'		=> $today,
					)
				),
				'orderby' => 'rand',
			);

			// NOTA: Agregar aparte query de exposiciones y talleres y usar merge_array en funcion de slider_deck()
			// NOTA: Falta if(custom week)

			slider_deck($args, 'sixs', $deckClass); ?>
		</div><?php




// colecciones
	elseif( get_row_layout() == 'op_collections' ):

		$or_title = mta_override_title();
		$post_objects = get_sub_field('collections');
		$count_objects = count($post_objects);
		if( $post_objects ): ?>
		<div class="area max_wrap collections <?php if($count_objects >= 2) echo 'single'; ?>" style="margin-bottom: 6em;" id="<?php echo mtn_cleanString($or_title); ?>">
			<?php if($or_title) echo '<h2 class="area_title">'.$or_title.'</h2>';

			if($count_objects > 1) { ?>
			<ul class="collection-controls"><?php
				foreach( $post_objects as $post):
					setup_postdata($post);
					$in = get_the_title();
					$Title = strlen($in) > 50 ? substr($in,0,50)."..." : $in; ?>
				<li><?php echo $Title; ?></li><?php
				endforeach;
				wp_reset_postdata(); ?>

			</ul><?php
			} ?>

			<div class="collection-slides"><?php
			foreach( $post_objects as $post):
				setup_postdata($post); ?>
				<div class="slide" id="slide_<?php the_ID(); ?>">
					<a href="<?php the_permalink(); ?>"><?php

						// ACF: Get image IDs
						$sizeM = get_field('coll_img_large');
						$sizeS = get_field('coll_img_mobile');

						if($sizeM || $sizeS) {
							if( $sizeM ) { echo wp_get_attachment_image( $sizeM, 'large', '', array( 'class' => 'img-large' ) ); }
							else { the_post_thumbnail('large', array( 'class' => 'img-large' ) ); }
							if( $sizeS ) echo wp_get_attachment_image( $sizeS, 'large', '', array( 'class' => 'img-mobile' ) );
						} else {
							the_post_thumbnail('large');
						} ?>
					</a>
				</div><?php
			endforeach;
			wp_reset_postdata(); ?>
			</div>
		</div><?php
		endif;



// conarteTV
	elseif( get_row_layout() == 'op_tv' ):

			if( have_rows('video_list') ): ?>
			<div class="white area conarte_tv">
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
