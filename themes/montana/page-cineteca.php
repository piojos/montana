<?php

	/* Template Name: Cineteca */
	get_header();


// First(Empty) Query ( .../eventos )
	$today = current_time('Ymd');
	$todayNice = date_i18n( 'l, M d Y', strtotime( $_GET['fecha'] ) );

	if(htmlentities($_GET['visibleFecha']) == '') $_GET['visibleFecha'] = $todayNice;
	if(htmlentities($_GET['fecha']) == '') $_GET['fecha'] = current_time('Ymd');

	if($_GET['fecha']) { $queryDay = $_GET['fecha']; }
	else { $queryDay = $today; }

	$args = array(
		'post_type'		=> 'cineteca',
		'numberposts'	=> 1,
		'meta_query' => array (
			array(
				'key'       => 'everyday',
				'value'     => $queryDay,
				'compare'   => 'LIKE',
			),
		)
	);

	$query = new WP_Query( $args ); ?>

	<section id="content" role="main"><?php


		$explore_events = get_field('ftd_events');

		$args = array(
			'post_type' => 'cineteca',
			'posts_per_page' => 4
		); ?>
		<div class="area head_blur">
			<?php

			if ($explore_events) { ?>
			<div class="deck max_wrap"><?php
				$count = getClassofQuery($explore_events);
				foreach( $explore_events as $post):
					setup_postdata($post);
					echo card($count);
				endforeach; ?>
			</div><?php

			} else {
				slider_deck($args, 'fours movie', 'max_wrap');
			}  ?>
		</div>

		<div class="area" id="agenda">
			<div class="max_wrap">
				<h2 class="area_title">Películas por día</h2>
				<p class="label">Estas viendo las funciones de:</p>
				<form role="search" method="get" id="searchfilter" class="searchform ag_filter" action="<?php echo esc_url( home_url('cineteca')); ?>">
					<div class="input_wrap <?php if($queryDay == $today) echo ' hoy'; ?>">
						<input type="text" id="visibleFecha" value="<?php echo $_GET['visibleFecha']; ?>">
						<input type="text" name="fecha" id="fecha" value="<?php echo $_GET['fecha']; ?>" style="display:none">
					</div>
					<input id="submit_filter" type="submit" value="Actualizar">
					<img class="loader" src="<?php echo get_template_directory_uri(); ?>/img/loader.gif" alt="">
				</form>
			</div>

			<div id="result_area" class="ag_results">
				<div class="internal"><?php

					if ( $query->have_posts() ) { ?>
					<ul><?php
						while ( $query->have_posts() ) {
							$query->the_post();
							list_card();
						} ?>
					</ul><?php
					} else { ?>
						<ul>
							<li>
								<div class="max_wrap">
									<div class="no-events">
										<h2>No hay peliculas en esta fecha.</h2>
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
