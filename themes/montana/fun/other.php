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
		$status .= '<br>[Range]';
	} else {
		$status .= '[ERROR]';
	}

	return $result;
	// return $result;
}

function schedule_days() {
	$allDaysArray = schedule_days_array();

	if($allDaysArray) {
		foreach($allDaysArray as $row) {
			$originalDate = $row;
			$newDate = date("F j Y", strtotime($originalDate));
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
