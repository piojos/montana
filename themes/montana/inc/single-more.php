<?php

	// if exposiciones
	// if ranged

	$today = current_time('Ymd');
	$wd1 = $today + 1;
	$wd2 = $wd1 + 1;
	$wd3 = $wd2 + 1;
	$wd4 = $wd3 + 1;
	$wd5 = $wd4 + 1;
	$wd6 = $wd5 + 1;
	$wd7 = $wd6 + 1;

	$coll_obj = get_field('collection_picker');
	$more_title = 'Más en '. ucfirst(get_post_type());
	$more_class = 'fours';


	if(is_singular('colecciones')) {
		$args = array(
			'post_type' => get_post_type(),
			'posts_per_page' => 8,
			'orderby' => 'rand',
		);

	} elseif($coll_obj) {
		$collID = $coll_obj->ID;
		$choose = get_field('options', $collID);
		$more_title = 'Mas en <a href="'.get_permalink($collID).'" class="link">'. $coll_obj->post_title.'</a>';
		if(is_singular('cineteca')) $more_class = 'fours movie';

		if($choose == 'auto') {
			$all_events = get_field('all_events', $coll_obj->ID );
			$args = array(
				'post_type' => get_post_type(),
				'post__in' => $all_events,
				'posts_per_page' => 8,
				'orderby' => 'rand',
			);
		}

	} elseif(is_singular('cineteca') || is_singular('agenda')) {

		if(is_singular('cineteca')) $more_class = 'fours movie';

		$args = array(
			'post_type' => get_post_type(),
			'posts_per_page' => 8,
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
		);
	} elseif(is_singular('post')) {
		$more_title = 'Más Noticias';
		$args = array(
			'post_type' => get_post_type(),
			'posts_per_page' => 8
		);
	} else {
		$args = array(
			'post_type' => get_post_type(),
			'meta_query' => array(
				array(
					'key' => 'range_date_picker_0_end_day',
					'value' => $today,
					'compare' => '>'
				),
			),
			'orderby' => 'rand',
		);
		// echo $today;
	}


	if($args) { ?>
		<div class="area max_wrap">
			<h2 class="area_title"><?php echo $more_title; ?></h2>
			<?php slider_deck($args, $more_class); ?>
		</div><?php
	}

?>
