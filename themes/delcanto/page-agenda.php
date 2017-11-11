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

	$iArgs = array(
		'post_type'		=> 'agenda',
		// 'numberposts'	=> 1,
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
/*
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
		</div> */ ?>

		<div class="area" id="agenda">
			<div class="max_wrap">
				<h2 class="area_title">o Busca Eventos por Fecha y Disciplina</h2>
				<p class="label">Estas viendo eventos de:</p>
				<div class="agenda_controls">
					<form role="search" method="get" id="top_day_controls" class="searchform ag_filter" action="<?php echo esc_url( home_url('agenda')); ?>">
						<div class="day_control flexbuttons"><?php

							// Agenda Day controls
							function agenda_dc_item($day) {
								$today = current_time('Ymd');
								$dc_format = 'D \<\s\t\r\o\n\g\> j \<\/\s\t\r\o\n\g\>';
								if( $day < $today ) {
									$class = ' class="past"';
								} elseif( $day == $today ) {
									$class = ' class="today"';
								}
								$string = '<button type="submit" name="fecha" value="'. $day .'"'.$class.'>'. date_i18n( $dc_format, strtotime( $day ) ) .'</button>';
								return $string;
							}

							echo agenda_dc_item($wdp1);
							echo agenda_dc_item($today);
							echo agenda_dc_item($wd1);
							echo agenda_dc_item($wd2);
							echo agenda_dc_item($wd3);
							echo agenda_dc_item($wd4);
							echo agenda_dc_item($wd5); ?>
						</div>
					</form>
					<form role="search" method="get" id="searchfilter" class="searchform ag_filter flexbuttons" action="<?php echo esc_url( home_url('agenda')); ?>">
						<div class="big input wrap <?php if($queryDay == $today) echo ' hoy'; ?> active">
							<label for="fecha">Fecha</label>
							<input type="text" id="visibleFecha" value="<?php echo $_GET['visibleFecha']; ?>" onchange="this.form.submit()">
							<input type="text" name="fecha" id="fecha" value="<?php echo $_GET['fecha']; ?>" style="display:none">
						</div>
						<div class="big input wrap">
							<label for="disciplina">Disciplina</label>
							<select name="disciplina" id="disciplina">
								<option value="">Todas</option>
								<?php echo listSelOptions('disciplinas'); ?>
							</select>
						</div>
						<div class="big input wrap">
							<label for="lugar">Espacio</label>
							<select name="lugar" id="lugar">
								<option value="">Todos</option>
								<?php echo listSelOptions('lugares'); ?>
							</select>
						</div>
					</form>
					<div class="loader">
						<img src="<?php echo get_template_directory_uri(); ?>/img/loader.gif" alt="">
					</div>
				</div>
			</div>

			<div id="result_area" class="ag_results">

				<div class="internal">
					<h3 class="max_wrap">Eventos CONARTE</h3><?php

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
					wp_reset_query(); ?>
			</div>
		</div>
	</section>

<?php get_footer(); ?>
