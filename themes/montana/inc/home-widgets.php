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
		if($count <= 2) {
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
			'posts_per_page' => 6,
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
