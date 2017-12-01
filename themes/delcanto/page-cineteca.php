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
		'meta_query'	=> array (
			array(
				'key'       => 'everyday',
				'value'     => $queryDay,
				'compare'   => 'LIKE',
			)
		),
	);

	$qdp1 = date('Ymd', strtotime("-1 day", strtotime($queryDay)));
	$qd1 = date('Ymd', strtotime("+1 day", strtotime($queryDay)));
	$qd2 = date('Ymd', strtotime("+2 day", strtotime($queryDay)));
	$qd3 = date('Ymd', strtotime("+3 day", strtotime($queryDay)));
	$qd4 = date('Ymd', strtotime("+4 day", strtotime($queryDay)));
	$qd5 = date('Ymd', strtotime("+5 day", strtotime($queryDay)));

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

		<div class="head area" id="agenda">
			<div class="max_wrap">
				<div class="titles">
					<h2><strong>Cineteca</strong> CON<strong>ARTE</strong></h2>
					<p class="subtitle">Costos regulares: $40 General | $25 Preferentes.</p>
				</div>
				<div class="action"></div>
				<div class="search_controls">
					<form role="search" method="get" id="searchfilter" class="searchform ag_filter" action="<?php echo esc_url( home_url('cineteca')); ?>">
						<div class="dates_control flexbuttons"><?php
							echo adc_searcher_day_item($qdp1);
							echo adc_searcher_day_item($queryDay, 'active');
							echo adc_searcher_day_item($qd1);
							echo adc_searcher_day_item($qd2);
							echo adc_searcher_day_item($qd3);
							echo adc_searcher_day_item($qd4);
							echo adc_searcher_day_item($qd5); ?>
						</div>
						<div class="flexbuttons">
							<div class="big input wrap select <?php if($queryDay == $today) echo ' hoy'; ?>">
								<label for="fecha">Fecha</label>
								<input type="text" id="visibleFecha" value="<?php echo $_GET['visibleFecha']; ?>" onchange="this.form.submit()">
								<input type="text" name="fecha" id="fecha" value="<?php echo $_GET['fecha']; ?>" style="display:none">
							</div>
					</form>
							<div class="big input wrap submit_button">
								<form id="searchmovies" action="<?php echo home_url(); ?>" method="get">
									<label for="fecha">Buscar Película</label>
									<input type="text" id="keyword" name="s" value="" onchange="">
									<button type="submit" name="post_type" id="searchsubmit" alt="Search" value="cineteca"><img src="<?php echo get_template_directory_uri(); ?>/img/icon_search.png" alt=""></button>
								</form>
							</div>
						</div>
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
						<h3>Peliculas el <span><?php echo date_i18n( 'l, M d Y', strtotime( $queryDay ) ); ?></span> </h3>
					</div><?php

					if ( $query->have_posts() ) { ?>
					<ul><?php
						while ( $query->have_posts() ) {
							$query->the_post();
							list_card($queryDay);
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
