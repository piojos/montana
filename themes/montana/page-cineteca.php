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

	<section id="content" role="main">
<?php /*
		<div class="area blurry_bg">
			<h2 class="area_title">No te pierdas</h2>
			<div class="deck max_wrap"><?php
				echo cards(2, 'twos'); ?>
			</div>
		</div>


		<div class="area" style="background:white;">
			<h2 class="area_title">Explora CONARTE</h2>
			<div class="deck max_wrap"><?php
				echo cards(6, 'fours'); // sixths?>
			</div>
		</div>
*/


 ?>

		<div class="area" id="agenda">
			<div class="max_wrap">
				<h2 class="area_title">Películas por día</h2>
				<p class="label">Estas viendo las funciones de:</p>
				<form role="search" method="get" id="searchform" class="searchform ag_filter" action="<?php echo esc_url( home_url('cineteca')); ?>">
					<div class="input_wrap hoy">
						<input type="text" id="visibleFecha" value="<?php echo $_GET['visibleFecha']; ?>">
						<input type="text" name="fecha" id="fecha" value="<?php echo $_GET['fecha']; ?>" style="display:none">
					</div>
					<input type="submit" value="Actualizar">
				</form>
			</div>

			<div class="ag_results">
				<div class="internal"><?php

					if ( $query->have_posts() ) { ?>
					<ul><?php
						while ( $query->have_posts() ) {
							$query->the_post();
							agenda_card();
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
