<?php


/*
 *	AGENDA: Get clean array from hours
 */

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




/*
 *	Get clean array from dates & range
 */

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
					$days[] = date('Ymd', $i);
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
			// 	$days[] = '<strong style="color:red;">'.$newDate.'</strong>';}
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
 *	MOVIES: Make array from days & hours
 */

// function movieSchedule_array() {
// 	$hours = array('holis');
// 	// $hoursField = get_field('schedules');
// 	return $hours;
// }


function movieDays($format) {
	if(empty($format)) $format = 'F j Y';
	$status = 'online';
	$days = array();
	$hours = array();
	if(have_rows('dates_picker')){ while (have_rows('dates_picker')) { the_row();
		$days[] = date_i18n($format, strtotime(get_sub_field('day')));
		if(have_rows('schedules')) { while (have_rows('schedules')) { the_row();
			$hours[] = get_sub_field('hour');
		}}
	}}
	$stDays = rtrim(implode(', ', $days), ',');
	$stHours = rtrim(implode('&nbsp; <span>•</span> &nbsp;', $hours), ' <span>•</span> ');
	$string = '<dt class="label">Fecha</dt><dd>'.$stDays.'</dd>';
	$string .= '<dt class="label">Horarios</dt><dd>'.$stHours.'</dd>';
	return $string;
}

// function movieHoursToday() {
// 	// Get movieSchedule_array
// 	// On day == today display hours

// 	$today = date('today');
// 	if(have_rows('dates_picker')){ while (have_rows('dates_picker')) { the_row();
// 		$days[] = date_i18n($format, strtotime(get_sub_field('day')));
// 		if(have_rows('schedules')) { while (have_rows('schedules')) { the_row();
// 			$hours[] = get_sub_field('hour');
// 		}}
// 	}}
// 	$string .= '<dt class="label">Horarios</dt><dd>'.$stHours.'</dd>';
// 	return $string;
// }



/*
 *	Process days schedules and update $everyday
 */

function my_acf_save_post( $post_id ) {
	$value = get_field('everyday');
	update_field('everyday', schedule_days_array());
}

add_action('acf/save_post', 'my_acf_save_post', 20);









/*
 *	Post Meta Box
 */

function single_metabox() {
	return 'hola!';
}
