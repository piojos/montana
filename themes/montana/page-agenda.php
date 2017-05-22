<?php

	/* Template Name: Agenda */
	get_header();


// First(Empty) Query ( .../eventos )
	if(htmlentities($_GET['date']) == "") {
		$_GET['fecha'] = date('Ymd');
		$_GET['tipo'] = '';
		$_GET['lugar'] = '';
	}

	$iArgs = array(
		'post_type'		=> 'agenda',
		'numberposts'	=> 1,
		'meta_query' => array (
			array(
				'key'       => 'everyday',
				'value'     => $today,
				'compare'   => 'LIKE',
			),
		)
	);

	// $eArgs = array(
	// 	'numberposts'	=> 2,
	// 	'post_type'		=> 'agenda',
	// );

	$int_query = new WP_Query( $iArgs );
	// $ext_query = new WP_Query( $eArgs ); ?>

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
				<h2 class="area_title">o Busca Eventos por Fecha y Disciplina</h2>
				<p class="label">Estas viendo eventos de:</p>
				<form class="ag_filter" action="#" method="post">
					<div class="input_wrap hoy">
						<input type="text" name="" value="Lunes Marzo 13, 2017">
					</div>
					<select class="" name="disciplinas">
						<option value="todas">Todas las Disciplinas</option>
						<option value="todas">Exposiciones</option>
						<option value="todas">Talleres</option>
						<option value="todas">Museos</option>
					</select>
					<select class="" name="disciplinas">
						<option value="todas">Todos las Espacios</option>
						<option value="todas">Exposiciones</option>
						<option value="todas">Talleres</option>
						<option value="todas">Museos</option>
					</select>
					<input type="submit" name="" value="Filtrar">
				</form>
			</div>

			<div class="ag_results">

				<div class="internal">
					<h3 class="max_wrap">Eventos CONARTE</h3><?php

					// $the_query = new WP_Query( $args );
					if ( $int_query->have_posts() ) { ?>
					<ul><?php
						while ( $int_query->have_posts() ) {
							$int_query->the_post();
							agenda_card();
						} ?>
					</ul><?php
					} else { ?>
						<ul>
							<li>
								<div class="max_wrap">
									<div class="no-events">
										<h2>No hay eventos hoy :(</h2>
										<p>Prueba con otra fecha o disciplina.</p>
									</div>
								</div>
							</li>
						</ul><?php
					}
						wp_reset_query();?>
				</div>
<?php /*
				<div class="external">
					<h3 class="max_wrap">Eventos Externos</h3><?php

					if ( $ext_query->have_posts() ) { ?>
					<ul><?php
						while ( $ext_query->have_posts() ) {
							$ext_query->the_post();
							agenda_card();
						} ?>
					</ul><?php
					} else { ?>
						<ul>
							<li>
								<div class="max_wrap">
									<div class="no-events">
										<h2>No hay eventos hoy :(</h2>
										<p>Prueba con otra fecha o disciplina.</p>
									</div>
								</div>
							</li>
						</ul><?php
					}
						wp_reset_query();?>
				</div>
*/ ?>
			</div>
		</div>
	</section>

<?php get_footer(); ?>
