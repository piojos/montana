<?php

	/* Template Name: Agenda */
	get_header();


// First(Empty) Query ( .../eventos )
	$today = current_time('Ymd');
	$wdp1 = $today - 1;
	$wd1 = $today + 1;
	$wd2 = $wd1 + 1;
	$wd3 = $wd2 + 1;
	$wd4 = $wd3 + 1;
	$wd5 = $wd4 + 1;
	$wd6 = $wd5 + 1;
	$wd7 = $wd6 + 1;
	$todayNice = date_i18n( 'l, M d Y', strtotime( $_GET['fecha'] ) );

	if(htmlentities($_GET['visibleFecha']) == '') $_GET['visibleFecha'] = $todayNice;
	if(htmlentities($_GET['fecha']) == '') $_GET['fecha'] = current_time('Ymd');
	if(htmlentities($_GET['disciplina']) == '') $_GET['disciplina'] = '';
	if(htmlentities($_GET['lugar']) == '') $_GET['lugar'] = '';

	if($_GET['fecha']) { $queryDay = $_GET['fecha']; }
	else { $queryDay = $today; }

	$cleanDay = filter_input( INPUT_GET, 'fecha', FILTER_SANITIZE_NUMBER_INT );

	$iArgs = array(
		'post_type'		=> 'agenda',
		'posts_per_page'	=> -1,
		'meta_key'		=> 'dates_picker_0_schedules_0_hour',
		'orderby'		=> 'meta_value_num',
		'order'			=> 'ASC',
		'meta_query' => array (
			'relation' => 'AND',
			array(
				'key'       => 'everyday',
				'value'     => $queryDay,
				'compare'   => 'LIKE',
			),
			array(
				'key'       => 'type',
				'value'     => 'int',
			),
		),
	);

	$eArgs = array(
		'post_type'		=> 'agenda',
		'meta_key'		=> 'dates_picker_0_schedules_0_hour',
		'orderby'		=> 'meta_value_num',
		'order'			=> 'ASC',
		'meta_query' => array (
			'relation' => 'AND',
			array(
				'key'       => 'everyday',
				'value'     => $queryDay,
				'compare'   => 'LIKE',
			),
			array(
				'key'       => 'type',
				'value'     => 'ext',
			),
		),
	);


	if($_GET['disciplina'] != '') {
		$disTaxArray = array( array(
			'taxonomy' => 'disciplinas',
			'field'    => 'slug',
			'terms'    => $_GET['disciplina'],
		));
		$iArgs[tax_query] = $disTaxArray;
		$eArgs[tax_query] = $disTaxArray;
	}

	if($_GET['lugar'] != '') {
		$lugTaxArray = array( array(
			'taxonomy' => 'lugares',
			'field'    => 'slug',
			'terms'    => $_GET['lugar'],
		));
		$iArgs[tax_query][] = $lugTaxArray;
		$eArgs[tax_query][] = $lugTaxArray;
	}


	if(($_GET['disciplina'] != '') || ($_GET['lugar'] != '')) {
		$fArgs = array(
			'post_type'		=> 'agenda',
			'posts_per_page'	=> -1,
			// 'meta_key'		=> 'dates_picker_0_schedules_0_hour',
			// 'orderby'		=> 'meta_value_num',
			// 'order'			=> 'ASC',
			// 'meta_query' => array (
			// 	array(
			// 		'key'       => 'everyday',
			// 		'value'     => $queryDay,
			// 		// 'type'		=> 'DATE',
			// 		'compare'   => 'LIKE',
			// 	)
			// ),
    'meta_query' => array(
        array(
            'key'     => 'everyday',
            'value'   => 20171130,
            'compare' => 'like',
            // 'type'    => 'NUMERIC'
        ),
    )
		);
		if($_GET['disciplina'] != '') $fArgs[tax_query] = $disTaxArray;
		if($_GET['lugar'] != '') $fArgs[tax_query][] = $lugTaxArray;
	}

	$int_query = new WP_Query( $iArgs );
	$ext_query = new WP_Query( $eArgs );
	if($fArgs) $fut_query = new WP_Query( $fArgs );


	// Receive downloadable Agenda file
	if( have_rows('current_agenda_link') ):
		while ( have_rows('current_agenda_link') ) : the_row();
			$f_m = get_sub_field('month');
			$f_y = get_sub_field('year');
			$file = get_sub_field('file_url');
		endwhile;
		else :
	endif;

	?>
	<section id="content" role="main"><?php
		$ftd_events = get_field('ftd_events');

		if( $ftd_events ): ?>
		<div class="area head_blur">
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
			'posts_per_page' => -1,
			'meta_query' => array(
				'relation' => 'OR',
				array('key' => 'everyday', 'value' => $today, 'compare' => 'LIKE',),
				array('key' => 'everyday', 'value' => $wd1, 'compare' => 'LIKE',),
				array('key' => 'everyday', 'value' => $wd2, 'compare' => 'LIKE',),
				array('key' => 'everyday', 'value' => $wd3, 'compare' => 'LIKE',),
				array('key' => 'everyday', 'value' => $wd4, 'compare' => 'LIKE',),
				array('key' => 'everyday', 'value' => $wd5, 'compare' => 'LIKE',),
				array('key' => 'everyday', 'value' => $wd6, 'compare' => 'LIKE',),
				array('key' => 'everyday', 'value' => $wd7, 'compare' => 'LIKE',)
			),
			'orderby' => 'rand',
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

		<div class="head area" id="agenda">
			<div class="max_wrap">
				<div class="titles">
					<h2><strong>Agenda</strong> CON<strong>ARTE</strong></h2>
					<p class="subtitle">Encuentra eventos por <strong>día</strong>, <strong>disciplina</strong> o <strong>espacio</strong>. <?php if($file) echo ' Descarga la <a href="'.$file.'" target="_blank"><strong>agenda de '.$f_m.' '.$f_y.'</strong></a>.'; ?></p>
				</div>
				<div class="action"></div>
				<div class="search_controls">
					<form role="search" method="get" id="searchfilter" class="searchform ag_filter" action="<?php echo esc_url( home_url('agenda')); ?>">
						<div class="dates_control flexbuttons"><?php
							echo adc_searcher_day_item($queryDay - 1);
							echo adc_searcher_day_item($queryDay, 'active');
							echo adc_searcher_day_item($queryDay + 1);
							echo adc_searcher_day_item($queryDay + 2);
							echo adc_searcher_day_item($queryDay + 3);
							echo adc_searcher_day_item($queryDay + 4);
							echo adc_searcher_day_item($queryDay + 5); ?>
						</div>
						<div class="flexbuttons">
							<div class="big input wrap select <?php if($queryDay == $today) echo ' hoy'; ?>">
								<label for="fecha">Fecha</label>
								<input type="text" name="visibleFecha" id="visibleFecha" value="<?php echo $_GET['visibleFecha']; ?>" onchange="this.form.submit()">
								<input type="text" name="fecha" id="fecha" value="<?php echo $_GET['fecha']; ?>" style="display:none">
							</div>
							<div class="big input wrap select">
								<label for="disciplina">Disciplina</label>
								<select name="disciplina" id="disciplina">
									<option value="">Todas</option>
									<?php echo listSelOptions('disciplinas'); ?>
								</select>
							</div>
							<div class="big input wrap select">
								<label for="lugar">Espacio</label>
								<select name="lugar" id="lugar">
									<option value="">Todos</option>
									<?php echo listSelOptions('lugares'); ?>
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
						<h3>Eventos CONARTE</h3>
					</div><?php

					if ( $int_query->have_posts() ) { ?>
					<ul><?php
						while ( $int_query->have_posts() ) {
							$int_query->the_post();
							list_card($queryDay);
						} ?>
					</ul><?php
					} else { ?>
						<ul>
							<li>
								<div class="max_wrap">
									<div class="no-events">
										<h2>No hay eventos hoy 😞</h2>
										<p>Prueba con otra fecha o disciplina.</p>
									</div>
								</div>
							</li>
						</ul><?php
					}
					wp_reset_query(); ?>
				</div><?php

					if ( $ext_query->have_posts() ) { ?>
				<div class="external">
					<h3 class="max_wrap">Eventos Externos</h3>
					<ul><?php
						while ( $ext_query->have_posts() ) {
							$ext_query->the_post();
							list_card($queryDay);
						} ?>
					</ul>
				</div><?php
				} else { /* ?>
						<ul>
							<li>
								<div class="max_wrap">
									<div class="no-events">
										<h2>No hay eventos hoy 😞</h2>
										<p>Prueba con otra fecha o disciplina.</p>
									</div>
								</div>
							</li>
						</ul><?php */
					}
					wp_reset_query();


				if($fArgs) { echo $cleanDay; ?>

				<div class="future">
					<div class="max_wrap">
						<h3>Eventos Futuros</h3>
					</div><?php
					if ( $fut_query->have_posts() ) { ?>
					<div class="max_wrap"><?php
						while ( $fut_query->have_posts() ) {
							$fut_query->the_post();
							the_field('everyday');
							card('fours','',$queryDay);
						} ?>
					</div><?php
					} else { ?>
						<ul>
							<li>
								<div class="max_wrap">
									<div class="no-events">
										<h2>No hay eventos futuros</h2>
										<p>Prueba con otra fecha o disciplina.</p>
									</div>
								</div>
							</li>
						</ul><?php
					}
					wp_reset_query(); ?>
				</div><?php
				} ?>
			</div>
		</div>
	</section>

<?php get_footer(); ?>
