<?php

$choose = get_field('options');
//
// 'all_ranged'

if($choose == 'auto') {
	$selEvents = get_field('all_events');
	$days_by_id = array();
	$just_days = array();
	$ids_by_day = array();


	// 1 Makes 'everyday' array for each ID
	foreach( $selEvents as $postID) {
		$ed = get_field('everyday', $postID);
		$tempA['id'] = $postID;
		$tempA['dates'] = $ed;
		$days_by_id[] = $tempA;
	}


	// 2 Makes ordered, unrepeated list of days
	foreach($days_by_id as $ids) {
		$just_days[] = array_values($ids['dates']);
	}
	$just_days_raw = array_reduce($just_days, 'array_merge', array());
	asort($just_days_raw);
	$just_days_nr = array_unique($just_days_raw);
	$just_days = array_values($just_days_nr);


	// 3 Makes list of IDs for every day
	foreach ($just_days as $day) {
		$thisDay = $day;
		$temp_dates = array();
		foreach($days_by_id as $idDates) {
			foreach($idDates['dates'] as $datesofid){
				if($datesofid == $thisDay) {
					$p = 1;
				}
			}
			if($p == 1) {
				$temp_dates[] = $idDates['id'];
			}
			$p = 0;
		}
		$tempB['day'] = $thisDay;
		$tempB['ids'] = $temp_dates;
		$ids_by_day[] = $tempB;
	} ?>

	<ul class="ag_results"><?php

	foreach ($ids_by_day as $dayM) { ?>
		<li>
			<div class="max_wrap">
				<h3> <?php
				$day = $dayM['day'];
				$newDate = date_i18n('l F d Y', strtotime($day));
				echo prefix_forDay($day, '<span>', '</span>').$newDate;
				 ?></h3>
			</div><?php

			$post_objects = $dayM['ids'];
			if( $post_objects ) { ?>
			<ul><?php
				foreach( $post_objects as $post) {
					setup_postdata($post);
					list_card($day);
				} ?>
			</ul><?php
			wp_reset_postdata();
			} ?>
		</li><?php
	} ?>
	</ul><?php



} elseif($choose == 'manual') {

	if(have_rows('event_days')) {
		while (have_rows('event_days')) {
			the_row(); ?>

			<ul class="ag_results">
				<li>
					<div class="max_wrap">
						<h3> <?php
						$day = get_sub_field('day');
						$newDate = date_i18n('l d \d\e F Y', strtotime($day));
						echo prefix_forDay($day, '<span>', '</span>').$newDate;
						 ?></h3>
					</div><?php

					$post_objects = get_sub_field('events');
					$this_args = array(
						'post_type' => array('agenda', 'cineteca'),
						'post__in' => $post_objects
					);

					$thisQ = new WP_Query($this_args);
					if($thisQ->have_posts()) { ?>
						<ul><?php
						while ($thisQ->have_posts()) {
							$thisQ->the_post();
							list_card($day);
						} ?>
					</ul><?php
						wp_reset_postdata();
					} ?>

				</li>
			</ul><?php
		}
	}

} else {
	// echo 'Error.';
}


$rangeIDs = get_field('all_ranged');
if(!empty($rangeIDs)) {
	$range_args = array(
		'post_type' => array('exposiciones', 'talleres'),
		'post__in' => $rangeIDs
	);
	$rangeQ = new WP_Query($range_args); ?>
<div class="area" style="padding: 3em 0 4em;">
	<div class="max_wrap"><h2 class="area_title">Exposiciones y Talleres</h2></div><?php
	if($rangeQ->have_posts()) { ?>
	<ul class="ag_results">
		<ul><?php
		while ($rangeQ->have_posts()) {
			$rangeQ->the_post();
			list_card($day);
		} ?>
		</ul>
	</ul><?php
		wp_reset_postdata();
	} ?>
</div><?php
} ?>
