<?php

	/* Template Name: Exposiciones */
	get_header();


	// filter
	// function my_posts_where( $where ) {
	// 	$where = str_replace("meta_key = 'range_date_picker_%", "meta_key LIKE 'range_date_picker_%", $where);
	// 	return $where;
	// }
	// add_filter('posts_where', 'my_posts_where');


// First(Empty)
	$post_slug = $post->post_name;
	$today = current_time('Ym\0\1');
	$todayNice = date_i18n( 'l, M d Y', strtotime( $_GET['fecha'] ) );

	if(htmlentities($_GET['visibleFecha']) == '') $_GET['visibleFecha'] = $todayNice;
	if(htmlentities($_GET['fecha']) == '') $_GET['fecha'] = current_time('Ym\0\1');

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

	get_template_part('inc/big', 'slider');	?>

		<div class="head area" id="agenda">
			<div class="max_wrap">
				<div class="titles">
					<h2>Busca <?php the_title(); ?></h2>
					<p class="subtitle">Planea tu visita explorando exposiciones por mes:</p>
				</div>
				<div class="action"></div>
				<div class="search_controls">
					<form role="search" method="get" id="searchfilter" class="searchform ag_filter" action="<?php echo esc_url( home_url($post_slug)); ?>">

					<div class="dates_control months flexbuttons"><?php
						$mp1 = date('Ymd', strtotime("-1 month", strtotime($queryDay)));
						$m1 = date('Ymd', strtotime("+1 month", strtotime($queryDay)));
						$m2 = date('Ymd', strtotime("+2 months", strtotime($queryDay)));
						$m3 = date('Ymd', strtotime("+3 months", strtotime($queryDay)));
						$m4 = date('Ymd', strtotime("+4 months", strtotime($queryDay)));
						$m5 = date('Ymd', strtotime("+5 months", strtotime($queryDay)));

						echo adc_searcher_month_item($mp1);
						echo adc_searcher_month_item($queryDay, 'active');
						echo adc_searcher_month_item($m1);
						echo adc_searcher_month_item($m2);
						echo adc_searcher_month_item($m3);
						echo adc_searcher_month_item($m4);
						echo adc_searcher_month_item($m5); ?>
					</div>
					<div class="flexbuttons">
						<input type="text" name="fecha" id="fecha" value="<?php echo $_GET['fecha']; ?>" style="display:none" onchange="this.form.submit()">
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
						<h3>Exposiciones en <span><?php echo date_i18n( 'F', strtotime( $queryDay ) ); ?></span> </h3>
					</div><?php

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
