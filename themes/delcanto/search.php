<?php get_header(); ?>

<section id="content" role="main">

	<div class="head area">
		<div class="max_wrap">
			<div class="titles">
				<h2>Buscaste: <?php echo get_search_query(); ?></h2>
				<p class="subtitle"><?php

				$postNum = $wp_query->found_posts;
				if($postNum == 0) {
					echo 'No encontramos resultados.';
				} else {
					if($postNum >= 2) { $plural = 's'; }
					echo 'Encontramos '.$postNum.' resultado'.$plural.'.';
				} ?></p>
			</div>
			<div class="action">
				<form id="local_searchform" action="<?php echo home_url(); ?>" method="get">
					<input class="inlineSearch" type="text" name="s" value="" placeholder="Buscar en CONARTE" />
					<input class="inlineSubmit" id="searchsubmit" type="submit" alt="Search" value="Buscar" />
				</form>
			</div>
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
