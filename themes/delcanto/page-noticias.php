<?php

	get_header();
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1; ?>

<section id="content" role="main">

	<div class="head area">
		<div class="max_wrap">
			<div class="titles">
				<h2><?php the_title(); ?></h2>
				<div class="subtitle"><?php echo get_field('override_excerpt'); ?></div>
			</div>
			<div class="action">
				<form id="local_searchform" action="<?php echo home_url(); ?>" method="get">
					<input type="hidden" name="post_type" value="post" />
					<input class="inlineSearch" type="text" name="s" value="" placeholder="Busca en Noticias" />
					<input class="inlineSubmit" id="searchsubmit" type="submit" alt="Search" value="Buscar" />
				</form>
			</div>
		</div>
	</div>
	<div class="white area">
		<div class="max_wrap"><?php

			$news_archive = new WP_Query( array('post_type' => 'post', 'posts_per_page' => 24, 'paged' => $paged ));

			if($news_archive->have_posts()) : ?>
				<div class="deck "><?php
				while ($news_archive->have_posts()) : $news_archive->the_post();
					echo card('fours');
				endwhile; ?>
				</div>
				<nav class="pagination"> <?php
					echo paginate_links( array(
						'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
						'total'        => $news_archive->max_num_pages,
						'current'      => max( 1, $paged ),
						'format'       => '?paged=%#%',
						'show_all'     => false,
						'type'         => 'plain',
						'end_size'     => 2,
						'mid_size'     => 1,
						'prev_next'    => true,
						'prev_text'    => sprintf( '<i></i> %1$s', __( '&larr;', 'text-domain' ) ),
						'next_text'    => sprintf( '%1$s <i></i>', __( '&rarr;', 'text-domain' ) ),
						'add_args'     => false,
						'add_fragment' => '',
					) ); ?>
				</nav><?php


				wp_reset_postdata();

			endif;



			// if ( have_posts() ) :
			// 	while ( have_posts() ) :
			// 		the_post();
			// 		echo card('fours');
			// 	endwhile;
								// the_posts_pagination( array(
								// 	'mid_size'  => 2,
								// 	'prev_text' => __( '&larr;' ),
								// 	'next_text' => __( '&rarr;' ),
								// 	'screen_reader_text' => ' '
								// ) );
			// endif; ?>
		</div>
	</div>
</section><?php



	get_footer(); ?>
