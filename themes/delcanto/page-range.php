<?php

	/* Template Name: Slider y Rango */
	get_header();


	// filter
	// function my_posts_where( $where ) {
	// 	$where = str_replace("meta_key = 'range_date_picker_%", "meta_key LIKE 'range_date_picker_%", $where);
	// 	return $where;
	// }
	// add_filter('posts_where', 'my_posts_where');


// First(Empty)
	$post_slug = $post->post_name;
	$today = current_time('Ymd');
	$todayNice = date_i18n( 'l, M d Y', strtotime( $_GET['fecha'] ) );

	if(htmlentities($_GET['visibleFecha']) == '') $_GET['visibleFecha'] = $todayNice;
	if(htmlentities($_GET['fecha']) == '') $_GET['fecha'] = current_time('Ymd');

	if($_GET['fecha']) { $queryDay = $_GET['fecha']; }
	else { $queryDay = $today; }


	$args = array(
		'post_type' => $post_slug,
		'meta_query' => array(
			// 'relation'		=> 'OR',
			array(
				'key'		=> 'range_date_picker_0_start_day',
				'compare'	=> '<=',
				'value'		=> $queryDay,
			),
			array(
				'key'		=> 'range_date_picker_0_end_day',
				'compare'	=> '>=',
				'value'		=> $queryDay,
			)
		),
	);




	$query = new WP_Query( $args ); ?>

	<section id="content" role="main"><?php

	get_template_part('inc/big', 'slider'); ?>

		<div class="area" id="agenda">
			<div class="max_wrap">
				<h2 class="area_title">Busca <?php the_title(); ?></h2>
				<p class="label">Estas viendo <?php echo $post_slug; ?> de:</p>
				<form role="search" method="get" id="searchfilter" class="searchform ag_filter" action="<?php echo esc_url( home_url($post_slug)); ?>">
					<div class="input_wrap <?php if($queryDay == $today) echo ' hoy'; ?>">
						<input type="text" id="visibleFecha" value="<?php echo $_GET['visibleFecha']; ?>">
						<input type="text" name="fecha" id="fecha" value="<?php echo $_GET['fecha']; ?>" style="display:none">
					</div>
					<input type="submit" value="Actualizar">
					<img class="loader" src="<?php echo get_template_directory_uri(); ?>/img/loader.gif" alt="">
				</form>
			</div>

			<div id="result_area" class="ag_results">
				<div class="internal">
					<?php // result_list($query, get_the_title(), $queryDay);

					if ( $query->have_posts() ) { ?>
					<ul><?php
						while ( $query->have_posts() ) {
							$query->the_post();
							list_card($day);
						} ?>
					</ul><?php
					} else { ?>
						<ul>
							<li>
								<div class="max_wrap">
									<div class="no-events">
										<h2>No hay <?php echo get_the_title(); ?> en esta fecha.</h2>
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
