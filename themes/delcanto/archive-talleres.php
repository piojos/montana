<?php

	/* Template Name: Talleres */
	get_header();

	$post_slug = $post->post_name;
	$today = current_time('Ymd');
	// $todayNice = date_i18n( 'l, M d Y', strtotime( $_GET['fecha'] ) );
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	// echo $paged;

	if(htmlentities($_GET['disciplina']) == '') $_GET['disciplina'] = '';
	if(htmlentities($_GET['lugar']) == '') $_GET['lugar'] = '';

	if($_GET['fecha']) { $queryDay = $_GET['fecha']; }
	else { $queryDay = $today; }


	$args = array(
		'post_type' => 'talleres',
		'meta_query' => array(
			array(
				'key'		=> 'range_date_picker_0_end_day',
				'compare'	=> '>=',
				'value'		=> $queryDay,
			)
		),
		'posts_per_page' => 12,
		'paged' => $paged
	);


	if($_GET['disciplina'] != '') {
		$args[tax_query] = array( array(
			'taxonomy' => 'disciplinas',
			'field'    => 'slug',
			'terms'    => $_GET['disciplina'],
		));
	}

	if($_GET['lugar'] != '') {
		$args[tax_query][] = array( array(
			'taxonomy' => 'lugares',
			'field'    => 'slug',
			'terms'    => $_GET['lugar'],
		));
	}



	$query = new WP_Query( $args ); ?>

	<section id="content" role="main"><?php

	get_template_part('inc/big', 'slider'); ?>

		<div class="head area" id="agenda">
			<div class="max_wrap">
				<div class="titles">
					<h2>Busca Talleres</h2>
					<p class="subtitle">Aprende a expresar algo nuevo.</p>
				</div>
				<div class="action"></div>
				<div class="search_controls">
					<form role="search" method="get" id="searchfilter" class="searchform ag_filter" action="<?php echo esc_url( home_url('talleres')); ?>">
						<div class="flexbuttons">
							<div class="big input wrap select">
								<label for="disciplina">Disciplina</label>
								<select name="disciplina" id="disciplina">
									<option value="">Todas</option>
									<?php
										// echo listSelOptions('disciplinas', $_GET['disciplina']);

										$termsD = get_terms( 'disciplinas' );
										if ( ! empty( $termsD ) && ! is_wp_error( $termsD ) ){ foreach ( $termsD as $term ) {
											$stringD .= '<option value="' . $term->slug . '"';
											if($term->slug == $_GET['disciplina']) $stringD .= ' selected';
											$stringD .= '>' . $term->name . '</option>';
										}}
										echo $stringD; ?>
								</select>
							</div>
							<div class="big input wrap select">
								<label for="lugar">Espacio</label>
								<select name="lugar" id="lugar">
									<option value="">Todos</option>
									<?php // echo listSelOptions('lugares', $_GET['lugar']);

										$termsE = get_terms( array( 'taxonomy' => 'lugares', 'parent' => 0 ) );
										if ( ! empty( $termsE ) && ! is_wp_error( $termsE ) ){ foreach ( $termsE as $term ) {
											$stringE .= '<option value="' . $term->slug . '"';
											if($term->slug == $_GET['lugar']) $stringE .= ' selected';
											$stringE .= '>' . $term->name . '</option>';
										}}
										echo $stringE;
									?>
								</select>
							</div>
						</div>
					</form>
					<div class="loader">
						<img src="<?php echo get_template_directory_uri(); ?>/img/loader.gif" alt="">
					</div>
				</div>
			</div>
		</div>

		<div class="area">
			<div id="result_area" class="ag_results">
				<div class="internal">
					<div class="max_wrap">
						<h3><?php
							$niceDisciplina = get_term_by('slug', $_GET['disciplina'], 'disciplinas');
							$niceLugar = get_term_by('slug', $_GET['lugar'], 'lugares');

							if($niceDisciplina || $niceLugar) {
								$listTitle = 'Talleres ';
								if($niceDisciplina) $listTitle .= ' de '.$niceDisciplina->name;
								if($niceLugar) $listTitle .= ' en '.$niceLugar->name;
							} else {
								$listTitle = 'Todos los Talleres';
							}
							echo $listTitle; ?></h3>
					</div>
					<?php

					if ( $query->have_posts() ) { ?>
					<ul><?php
						while ( $query->have_posts() ) {
							$query->the_post();
							list_card($day);
						} ?>
					</ul><nav class="pagination dynamic"><?php
						echo paginate_links( array(
							'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999, false ) ) ),
							'total'        => $query->max_num_pages,
							'current'      => max( 1, $paged ),
							'format'       => 'page/%#%',
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
					} else { ?>
						<ul>
							<li>
								<div class="max_wrap">
									<div class="no-events">
										<h2>No hay Talleres en esta fecha.</h2>
									</div>
								</div>
							</li>
						</ul><?php
					}
					wp_reset_query(); ?>
				</div>
			</div>
		</div>
	</section>

<?php get_footer(); ?>
