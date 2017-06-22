<?php

	/* Template Name: Agenda */
	get_header();


// First(Empty) Query ( .../eventos )
	$today = current_time('Ymd');
	$todayNice = date_i18n( 'l, M d Y', strtotime( $_GET['fecha'] ) );

	if(htmlentities($_GET['visibleFecha']) == '') $_GET['visibleFecha'] = $todayNice;
	if(htmlentities($_GET['fecha']) == '') $_GET['fecha'] = current_time('Ymd');
	if(htmlentities($_GET['disciplina']) == '') $_GET['disciplina'] = '';
	if(htmlentities($_GET['lugar']) == '') $_GET['lugar'] = '';

	if($_GET['fecha']) { $queryDay = $_GET['fecha']; }
	else { $queryDay = $today; }

	$iArgs = array(
		'post_type'		=> 'agenda',
		'numberposts'	=> 1,
		'meta_key'		=> 'type',
		'meta_value'	=> 'int',
		'meta_query' => array (
			array(
				'key'       => 'everyday',
				'value'     => $queryDay,
				'compare'   => 'LIKE',
			),
		),
	);

	$eArgs = array(
		'post_type'		=> 'agenda',
		'numberposts'	=> 1,
		'meta_key'		=> 'type',
		'meta_value'	=> 'ext',
		'meta_query' => array (
			array(
				'key'       => 'everyday',
				'value'     => $queryDay,
				'compare'   => 'LIKE',
			),
		),
	);

	if($_GET['disciplina'] != '') {
		$iArgs[tax_query] = array( array(
			'taxonomy' => 'disciplinas',
			'field'    => 'slug',
			'terms'    => $_GET['disciplina'],
		));
		$eArgs[tax_query] = array( array(
			'taxonomy' => 'disciplinas',
			'field'    => 'slug',
			'terms'    => $_GET['disciplina'],
		));
	}

	if($_GET['lugar'] != '') {
		$iArgs[tax_query][] = array( array(
			'taxonomy' => 'lugares',
			'field'    => 'slug',
			'terms'    => $_GET['lugar'],
		));
		$eArgs[tax_query][] = array( array(
			'taxonomy' => 'lugares',
			'field'    => 'slug',
			'terms'    => $_GET['lugar'],
		));
	}


	$int_query = new WP_Query( $iArgs );
	$ext_query = new WP_Query( $eArgs ); ?>

	<section id="content" role="main"><?php

		$ftd_events = get_field('ftd_events');

		if( $ftd_events ): ?>
		<div class="area head_agenda">
			<div class="max_wrap">
				<h2 class="area_title">No te pierdas</h2>
				<div class="deck slider_deck"><?php
				$count = getClassofQuery($ftd_events);
				foreach( $ftd_events as $post):
					setup_postdata($post);
					echo card($count);
				endforeach; ?>
				</div>
			</div>
		</div><?php
			wp_reset_postdata();
		endif;




		$explore_events = get_field('explore_events');

		$args = array(
			'post_type' => 'agenda',
			'posts_per_page' => 8
		); ?>
		<div class="area explore">
			<div class="max_wrap">
				<h2 class="area_title">Explora CONARTE</h2>
				<?php

				if ($explore_events) { ?>
				<div class="deck slider_deck"><?php
					$count = getClassofQuery($explore_events);
					foreach( $explore_events as $post):
						setup_postdata($post);
						echo card($count);
					endforeach; ?>
				</div><?php

				} else {
					slider_deck($args, 'sixs', 'max_wrap');
				}  ?>
			</div>
		</div>

		<div class="area" id="agenda">
			<div class="max_wrap">
				<h2 class="area_title">o Busca Eventos por Fecha y Disciplina</h2>
				<p class="label">Estas viendo eventos de:</p>
				<form role="search" method="get" id="searchform" class="searchform ag_filter" action="<?php echo esc_url( home_url('agenda')); ?>">
					<div class="input_wrap <?php if($queryDay == $today) echo ' hoy'; ?>">
						<input type="text" id="visibleFecha" value="<?php echo $_GET['visibleFecha']; ?>">
						<input type="text" name="fecha" id="fecha" value="<?php echo $_GET['fecha']; ?>" style="display:none">
					</div>
					<select name="disciplina" id="disciplina">
						<option value="">Todas las Disciplinas</option>
						<?php echo listSelOptions('disciplinas'); ?>
					</select>
					<select name="lugar" id="lugar">
						<option value="">Todos las Espacios</option>
						<?php echo listSelOptions('lugares'); ?>
					</select>
					<input type="submit" value="Actualizar">
				</form>
			</div>

			<div class="ag_results">

				<div class="internal">
					<h3 class="max_wrap">Eventos CONARTE</h3><?php

					if ( $int_query->have_posts() ) { ?>
					<ul><?php
						while ( $int_query->have_posts() ) {
							$int_query->the_post();
							list_card();
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
					wp_reset_query(); ?>
				</div>
				<div class="external">
					<h3 class="max_wrap">Eventos Externos</h3><?php

					if ( $ext_query->have_posts() ) { ?>
					<ul><?php
						while ( $ext_query->have_posts() ) {
							$ext_query->the_post();
							list_card();
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
					wp_reset_query(); ?>
				</div>
			</div>
		</div>
	</section>

<?php get_footer(); ?>
