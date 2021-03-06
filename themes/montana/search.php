<?php get_header(); ?>

<section id="content" role="main">

	<div class="area">
		<div class="max_wrap">
			<h2 class="area_title">Buscaste: <?php echo get_search_query(); ?></h2>
			<p><?php

			$postNum = $wp_query->found_posts;
			if($postNum == 0) {
				echo 'No encontramos resultados.';
			} else {
				if($postNum >= 2) { $plural = 's'; }
				echo 'Encontramos '.$postNum.' resultado'.$plural.'.';
			} ?></p>

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
