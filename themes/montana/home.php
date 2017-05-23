<?php

	get_header();
	$today = current_time('Ymd'); ?>


	<section id="content" role="main" class="home">
	<br><br><br><?php

// slider









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
