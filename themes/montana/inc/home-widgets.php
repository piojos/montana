<?php

	function runHomeWidget($title = '', $args, $class = 'fours') { ?>

		<div class="area max_wrap">
			<?php if($title) echo '<h2 class="area_title">'.$title.'</h2>'; ?>
			<?php deck($args, $class); ?>
		</div><?php
	}

if( have_rows('bloques_principales') ): while ( have_rows('bloques_principales') ) : the_row();

// #queHacerHoy
	if( get_row_layout() == 'op_today_events' ):
	// jQuery: contar cards y distribuír de acuerdo al número.
		$today = current_time('Ymd');
		$args = array(
			'post_type' => 'agenda',
			'orderby' => 'rand',
			'posts_per_page' => 4,
			'meta_query' => array (
				array(
					'key'       => 'everyday',
					'value'     => $today,
					'compare'   => 'LIKE',
				),
			)
		);
		$custom_posts = new WP_Query($args);
		$count = $custom_posts->post_count;
		if($count < 2) {
			$class = ' twos';
		} else {
			$class = ' fours';
		}
		runHomeWidget('¿Qué hacer hoy?', $args, $class);




// Cineteca
	elseif( get_row_layout() == 'op_today_movies' ):

		$args = array(
			'post_type' => 'cineteca',
			'posts_per_page' => 4,
			'meta_query' => array (
				array(
					'key'       => 'everyday',
					'value'     => $today,
					'compare'   => 'LIKE',
				),
			)
		);
		runHomeWidget('Hoy en Cineteca', $args, 'fours movie');




// esta semana
	elseif( get_row_layout() == 'op_week_events' ):
	// jQuery: contar cards y distribuír de acuerdo al número.

		$args = array(
			'post_type' => 'agenda',
			'posts_per_page' => 12,
		);
		runHomeWidget('Esta Semana', $args, 'sixs');




// colecciones
	elseif( get_row_layout() == 'op_collections' ):

		$post_objects = get_sub_field('collections');

		if( $post_objects ): ?>
		<div class="area max_wrap collections">
			<h2 class="area_title">No te pierdas</h2>
			<div class="controls">
				<ul><?php
				foreach( $post_objects as $post):
					setup_postdata($post); ?>
					<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li><?php
				endforeach; ?>
				</ul>
			</div>
		</div><?php
		wp_reset_postdata();
		endif;

		// <div class="details_container">
			// <div class="info column">
			// 	<h1>Callegenera</h1>
			// 	<span>Slogan</span>
			// 	<p class="about">wara wara</p>
			//
			// 	<div class="status_label">
			// 		<p><strong>Hasta Marzo 14 (is this dinamic?)</strong></p>
			// 		<p>Location Taxonomy</p>
			// 	</div>
			// </div>
			// <div class="gallery column">
			// 	<a href="#" class="full"><img src="http://placehold.it/780x400" alt=""></a>
			// 	<a href="#" class="half"><img src="http://placehold.it/380x250" alt=""></a>
			// 	<a href="#" class="half"><img src="http://placehold.it/380x250" alt=""></a>
			// 	<a href="#" class="half"><img src="http://placehold.it/380x250" alt=""></a>
			// 	<a href="#" class="half more"> Ver todo</a>
			// </div>
		// </div>




// conarteTV
	elseif( get_row_layout() == 'op_tv' ):
	// CPT? Options? YT?


	elseif( get_row_layout() == 'op_soon' ):

		$post_objects = get_sub_field('select_soon');

		if( $post_objects ): ?>
		<div class="area max_wrap collections">
			<h2 class="area_title">Próximamente</h2>
			<div class="deck"><?php
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
