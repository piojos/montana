<?php

function schedule_hours_array() {
	$hours = array();
	$hoursField = get_field('schedules');
	if($hoursField) {
		foreach($hoursField as $hour) {
			$hours[] = $hour['hour'];
		}
	}
	return $hours;
}

function schedule_hours() {
	$allHours = schedule_hours_array();
	$string = rtrim(implode(', ', $allHours), ',');
	return $string;
}

function schedule_days_array() {
	// 1 Get chosen method
	// NOTES: add if $field else ⬇
	$chosenOne = get_field('dates_options');
	$status = $chosenOne;
	if(!empty($chosenOne)) {$status = 'field: ok';} else {$status = 'field: ERROR';}


	if($chosenOne == 'dates') {

		$status .= '<br>[Dates]: ';
		$days = array();
		$rows = get_field('dates_picker');
		if($rows) {
			foreach($rows as $row) {
				$days[] = $row['day'];
			}
		}
		$status .= $days;
		$result = $days;

	} elseif($chosenOne == 'range') {

		$status .= '<br>[Range]: ';
		$days = array();

		if( have_rows('range_date_picker') ) { while ( have_rows('range_date_picker') ) { the_row();
			$startDate = get_sub_field('start_day');
			$endDate = get_sub_field('end_day');
			$weekdays = get_sub_field('weekdays');
		}}

		// loop per selected weekday
		if($weekdays) {
			foreach($weekdays as $row) {
				for($i = strtotime($row, strtotime($startDate)); $i <= strtotime($endDate); $i = strtotime('+1 week', $i)) {
					$days[] = date('l dM', $i);
				}
			}
		}

		$status .= $days;
		$result = $days;

	} else {
		$status .= '[ERROR]';
	}

	// return $status;
	return $result;
}

function schedule_days($format) {
	if(empty($format)) $format = 'F j Y';
	$allDaysArray = schedule_days_array();

	if($allDaysArray) {
		foreach($allDaysArray as $row) {
			$originalDate = $row;
			$newDate = date($format, strtotime($originalDate));
			// $today = date('Ymd');
			// if($today == $originalDate) {
			// 	$days[] = '<strong>'.$newDate.'</strong>';}
			// else {
				$days[] = $newDate;
			// }
		}
	}
	$string = rtrim(implode(', ', $days), ',');

	// Check with today
	return $string;
}





/*
 *	Guardar un campo con un arreglo de
 */

function my_acf_save_post( $post_id ) {

    // get new value
    $value = get_field('everyday');


    // do somethingy
	update_field('everyday', schedule_days_array());
    // update field to value 'working'
}

add_action('acf/save_post', 'my_acf_save_post', 20);









/*
 *	Legalize Date repeater field
 */
	//
	// function my_posts_where( $where ) {
	// 	$where = str_replace("meta_key = 'days_%_date", "meta_key LIKE 'days_%_date", $where);
	// 	// $where = str_replace("meta_key = 'dates_%_start-day", "meta_key LIKE 'dates_%_start-day", $where);
	// 	// $where = str_replace("meta_key = 'dates_%_end-day", "meta_key LIKE 'dates_%_end-day", $where);
	// 	return $where;
	// }
	// add_filter('posts_where', 'my_posts_where');
