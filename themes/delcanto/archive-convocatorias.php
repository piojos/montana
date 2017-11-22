<?php

	/* Template Name: Talleres */
	get_header();

	$post_slug = $post->post_name;
	$today = current_time('Ym\0\1');
	// $todayNice = date_i18n( 'l, M d Y', strtotime( $_GET['fecha'] ) );
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	// echo $paged;

	if(htmlentities($_GET['disciplina']) == '') $_GET['disciplina'] = '';
	// if(htmlentities($_GET['lugar']) == '') $_GET['lugar'] = '';

	if($_GET['fecha']) { $queryDay = $_GET['fecha']; }
	else { $queryDay = $today; }


	$args = array(
		'post_type' => 'convocatorias',
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

	// if($_GET['lugar'] != '') {
	// 	$args[tax_query][] = array( array(
	// 		'taxonomy' => 'lugares',
	// 		'field'    => 'slug',
	// 		'terms'    => $_GET['lugar'],
	// 	));
	// }



	$query = new WP_Query( $args ); ?>

	<section id="content" role="main"><?php

	get_template_part('inc/big', 'slider'); ?>

		<div class="head area" id="agenda">
			<div class="max_wrap">
				<div class="titles">
					<h2>Busca Convocatorias</h2>
					<p class="subtitle">¡Involúcrate con la cultura y las artes! Explora nuestras convocatorias por disciplina:</p>
				</div>
				<div class="action"></div>
				<div class="search_controls">
					<form role="search" method="get" id="searchfilter" class="searchform ag_filter" action="<?php echo esc_url( home_url('convocatorias')); ?>">
						<div class="flexbuttons">
							<div class="big input wrap select">
								<label for="disciplina">Disciplina</label>
								<select name="disciplina" id="disciplina">
									<option value="">Todas</option>
									<?php
										// echo listSelOptions('disciplinas', $_GET['disciplina']);

										$terms = get_terms( 'disciplinas' );
										if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ foreach ( $terms as $term ) {
											$string .= '<option value="' . $term->slug . '"';
											if($term->slug == $_GET['disciplina']) $string .= ' selected';
											$string .= '>' . $term->name . '</option>';
										}}
										echo $string; ?>
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

							if($niceDisciplina || $niceLugar) {
								$listTitle = 'Convocatorias ';
								if($niceDisciplina) $listTitle .= ' de '.$niceDisciplina->name;
							} else {
								$listTitle = 'Todas las Convocatorias';
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
					</ul><nav class="pagination"><?php
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
										<h2>No hay Convocatorias en esta fecha.</h2>
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
