<?php

	get_header(); ?>

<section id="content" role="main">

	<div class="head area">
		<div class="max_wrap">
			<h2>Servicios permanentes</h2>
			<p class="subtitle">Actividades y servicios para todo el año.</p>
		</div>
	</div>
	<div class="white area">
		<div class="max_wrap">
			<div class="deck "><?php

			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					echo card('fours');
				endwhile;
				the_posts_pagination( array(
					'mid_size'  => 2,
					'prev_text' => __( '&larr;' ),
					'next_text' => __( '&rarr;' ),
					'screen_reader_text' => ' '
				) );
			endif; ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
