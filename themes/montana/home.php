<?php

	get_header();
	$today = current_time('Ymd'); ?>


	<section id="content" role="main" class="home"><?php

// slider
	$sliderPosts = get_field('featured_slider', 2);

	if($sliderPosts) {
		echo '<div class="home slider">';
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
						<div class="status_label">
							<p><strong>Hasta Marzo 14</strong></p>
							<p>Location Taxonomy</p>

							<a href="#" class="share"><img src="http://placehold.it/24" alt=""></a>
							<a href="#" class="share"><img src="http://placehold.it/24" alt=""></a>
						</div>
					</div>
				</div>
			</div><?php
			}
		echo '</div>';
	}








// Noticias

	$latestQuery = new WP_Query( array('post_type' => 'post', 'posts_per_page' => 1 ) );
	$restQuery = new WP_Query( array('post_type' => 'post', 'posts_per_page' => 6 , 'offset' => 1) );

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








// #queHacerHoy
	// jQuery: contar cards y distribuír de acuerdo al número. ?>

		<div class="area max_wrap">
			<h2 class="area_title">¿Qué hacer hoy?</h2><?php

			$args = array(
				'post_type' => 'agenda',
				'posts_per_page' => 4,
				'meta_query' => array (
					array(
						'key'       => 'everyday',
						'value'     => $today,
						'compare'   => 'LIKE',
					),
				)
			);
			deck($args, 'fours'); ?>
		</div><?php








// cineteca
	// Mostrar "por orden"?
	// Conectar "ver todas" ?>

		<div class="area max_wrap">
			<h2 class="area_title">Cineteca</h2><?php

			$args = array(
				'post_type' => 'cineteca',
				'posts_per_page' => 4,
				// 'meta_query' => array (
				// 	array(
				// 		'key'       => 'everyday',
				// 		'value'     => $today,
				// 		'compare'   => 'LIKE',
				// 	),
				// )
			);
			deck($args, 'fours movie'); ?>
		</div><?php








// conarteTV
	// CPT? Options? YT? ?>

		<?php




// colecciones ?>

	<div class="area max_wrap collections">
		<h2 class="area_title">No te pierdas</h2><?php

		$args = array(
			'post_type' => 'colecciones',
			'posts_per_page' => -1
		);

		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) { ?>
		<div class="controls">
			<ul><?php
			while ( $the_query->have_posts() ) {
				$the_query->the_post(); ?>
				<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li><?php
			} ?>
			</ul>
		</div><?php
			wp_reset_postdata();
		}
		// <div class="details_container">
		// </div> ?>
	</div>
		<?php




// esta semana
	// jQuery: contar cards y distribuír de acuerdo al número. ?>

		<div class="area max_wrap">
			<h2 class="area_title">Esta Semana</h2><?php

			$args = array(
				'post_type' => 'agenda',
				'posts_per_page' => 12,
				// 'meta_query' => array (
				// 	array(
				// 		'key'       => 'everyday',
				// 		'value'     => $today,
				// 		'compare'   => 'LIKE',
				// 	),
				// )
			);
			deck($args, 'sixs'); ?>
		</div><?php








// Próximamente ?>
		<div class="area max_wrap">
			<h2 class="area_title">Próximamente</h2><?php

			$args = array(
				'post_type' => 'agenda',
				'posts_per_page' => 2,
			);
			deck($args, 'twos'); ?>
		</div>



	</section>

<?php

	get_footer(); ?>
