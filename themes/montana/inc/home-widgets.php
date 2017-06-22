<?php

	function runHomeWidget($title = '', $args, $class = 'fours') { ?>

		<div class="area max_wrap">
			<?php if($title) echo '<h2 class="area_title">'.$title.'</h2>'; ?>
			<?php slider_deck($args, $class); ?>
		</div><?php
	}

if( have_rows('bloques_principales') ): while ( have_rows('bloques_principales') ) : the_row();

// #queHacerHoy
	if( get_row_layout() == 'op_today_events' ):
	// jQuery: contar cards y distribuír de acuerdo al número.

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
			<h2 class="area_title">¿Qué hacer hoy?</h2>
			<?php slider_deck($selToday, $class, '', true); ?>
		</div><?php

		} else {
			$args = array(
				'post_type' => 'agenda',
				'posts_per_page' => 6,
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
			<h2 class="area_title">¿Qué hacer hoy?</h2>
			<?php slider_deck($args, $class, '', true); ?>
		</div><?php
			// runHomeWidget('¿Qué hacer hoy?', $args, $class);
		}





// Cineteca
	elseif( get_row_layout() == 'op_today_movies' ):

		$selMovies = get_sub_field('or_movies');
		if($selMovies) {
			$args = array(
				'post_type' => 'cineteca',
				'post__in' => $selMovies
			);
		} else {
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
		runHomeWidget('Hoy en Cineteca', $args, 'fours movie');




// esta semana
	elseif( get_row_layout() == 'op_week_events' ):
	// jQuery: contar cards y distribuír de acuerdo al número.

		$args = array(
			'post_type' => 'agenda',
			'posts_per_page' => 10,
		); ?>
		<div class="area max_wrap ">
			<h2 class="area_title">Esta Semana</h2><?php

			$ftd_post = get_sub_field('featured');

			if($ftd_post) {
				foreach ($ftd_post as $post) {
					setup_postdata($post);
					echo '<div class="row threes ftd_row">';
					card('threes week_ftd_post', $post);
					echo '</div>';
				}
				wp_reset_postdata();
			}

			slider_deck($args, 'sixs', 'this_week'); ?>
		</div><?php




// colecciones
	elseif( get_row_layout() == 'op_collections' ):

		$post_objects = get_sub_field('collections');

		if( $post_objects ): ?>
		<div class="area max_wrap collections" style="margin-bottom: 6em;">
			<h2 class="area_title">No te pierdas</h2>
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
								<p><strong>Hasta Marzo 14</strong></p>
								<p>Location Taxonomy</p>
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

		$post_objects = get_sub_field('select_soon');

		if( $post_objects ): ?>
		<div class="area max_wrap">
			<h2 class="area_title">Próximamente</h2>
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
