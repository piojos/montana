<?php

	get_header(); ?>


	<section id="content" role="main" class="home">
<br><br><br>
		<?php // slider


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
		} ?>
			wp_reset_postdata();
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
	} ?>



		<?php // que hacer hoy ?>
		<?php // cineteca ?>
		<?php // conarteTV ?>
		<?php // colecciones ?>
		<?php // esta semana ?>



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
