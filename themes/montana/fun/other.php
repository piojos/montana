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
						$days[] = current_time('Ymd', $i);
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


	function schedule_days($format = 'F j Y') {
		$chosenOne = get_field('dates_options');
		$allDaysArray = schedule_days_array();
		if($chosenOne == 'dates') {
			if($allDaysArray) {
				foreach($allDaysArray as $row) {
					$originalDate = $row;
					$newDate = date_i18n($format, strtotime($originalDate));
					$today = current_time('Ymd');
					// if($today == $originalDate) {
					// 	$days[] = '<strong style="color:red;">'.$newDate.'</strong>';}
					// else {
						$days[] = $newDate;
					// }
				}
			}
			$string = rtrim(implode(', ', $days), ',');
		} elseif($chosenOne == 'range') {
			if(have_rows('range_date_picker')) { while (have_rows('range_date_picker')) {
				the_row();
				$start_day = get_sub_field('start_day');
				$end_day = get_sub_field('end_day');
				$weekdays = get_sub_field('weekdays');
			}}
			if($weekdays) {
				$string = createRangeWeekdays($weekdays).'<br>';
			}
			$start_day = date_i18n('d \d\e F', strtotime($start_day));
			$end_day = date_i18n('d \d\e F Y', strtotime($end_day));
			$string .= 'Del '. $start_day .' al '. $end_day;
		} else {
			$status .= '[ERROR]';
		}

		// Check with today
		return $string;
	}


	function createRangeWeekdays($weekdays = '') {
		if($weekdays) {
			$wdString = implode(", ", $weekdays);
			$codex = array("mon", "tue", "wed", "thu", "fri", "sat", "sun");
			$nice = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sabado", "Domingo");
			$string = str_replace($codex, $nice, $wdString);
		} else {
			$string = 'error';
		}
		return $string;
	}


/*
 *	MOVIES: Make array from days & hours
 */

	function movieSchedule_array($format) {
		if(empty($format)) $format = 'Ymd';
		$status = 'online';
		if(have_rows('dates_picker')){ while (have_rows('dates_picker')) { the_row();
			$day = date_i18n($format, strtotime(get_sub_field('day')));
			if(have_rows('schedules')) {
				$hours = array();
				while (have_rows('schedules')) { the_row();
				$hours[] = get_sub_field('hour');
				}
			}
			$daysList[] = array($day, $hours);
		}}
		return $daysList;
	}


	function movieDays_array($format = 'Ymd') {
		$status = 'online';
		$days = array();
		if(have_rows('dates_picker')){ while (have_rows('dates_picker')) { the_row();
			$days[] = date_i18n($format, strtotime(get_sub_field('day')));
		}}
		return $days;
	}


	function movieDays($format = 'F j Y') {
		$status = 'online';
		$days = array();
		// $hours = array();
		if(have_rows('dates_picker')){ while (have_rows('dates_picker')) { the_row();
			$days[] = date_i18n($format, strtotime(get_sub_field('day')));
			// if(have_rows('schedules')) { while (have_rows('schedules')) { the_row();
			// 	$hours[] = get_sub_field('hour');
			// }}
		}}
		$stDays = rtrim(implode(', ', $days), ',');
		// $stHours = rtrim(implode('&nbsp; <span>•</span> &nbsp;', $hours), ' <span>•</span> ');
		$string = '<dt class="label">Fechas</dt><dd style="text-transform:capitalize;">'.$stDays.'</dd>';
		// $string .= '<dt class="label">Horarios</dt><dd>'.$stHours.'</dd>';
		return $string;
	}


	function movieFutureSchedule_array() {
		$schedArray = movieSchedule_array('Ymd');
		$today = current_time('Ymd');
		$new = array_filter($schedArray, function ($var) use ($today) {
			return ($var[0] >= $today);
		});
		$new = array_values($new);
		return $new;
	}


	function movieHoursClosestday() {
		$schedArray = movieSchedule_array('Ymd');
		$today = current_time('Ymd');
		$new = array_filter($schedArray, function ($var) use ($today) {
			return ($var[0] >= $today);
		});
		$new = array_values($new);
		foreach ($new[0] as $key) {
			$gethours[1] = $key;
		}
		if(is_array($gethours[1])){
			$hours = '<ul class="moviehours">';
			foreach ($gethours[1] as $hour) {
				$hours .= '<li>'.$hour.'</li>';
			}
			$hours .= '</ul>';
		}
		return $hours;
		// return $new;
	}


	function prefix_forDay($any_day) {
		$today = current_time('Ymd');
		if($today == $any_day) $string = '<strong>HOY </strong> | ';
		if($today+1 == $any_day) $string = '<strong>MAÑANA </strong> | ';
		return $string;
	}




/*
 *	Process days schedules and update $everyday
 */

	function everydayGen() {
		$post_type = get_post_type($post_id);
		if ($post_type == 'agenda') {
			$result = schedule_days_array();
		} elseif ($post_type == 'cineteca') {
			$result = movieDays_array();
		} else {}
		return $result;
	}



	function my_acf_save_post( $post_id ) {
		$value = get_field('everyday');
		update_field('everyday', everydayGen());
	}

	add_action('acf/save_post', 'my_acf_save_post', 20);








/*
*	Generate option list for Select dropdown
*/

	function listSelOptions($term) {
		$terms = get_terms( $term );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ foreach ( $terms as $term ) {
			$string .= '<option value="' . $term->slug . '">' . $term->name . '</option>';
		}}
		return $string;
	}








/*
 *	Test disciplina & lugar within post loop
 */

	function testPlaceSkill() {
		$lugarTerm = get_term( get_field('location_picker'), 'lugares');
		$skillArray = get_field('skill_picker');
		$string = 'lugar: '.$lugarTerm->slug;
		$string .= '<br>disciplina: ';
		foreach ($skillArray as $skill) {
			$skillTerm = get_term( $skill, 'disciplinas' );
			$string .= $skillTerm->slug;
		}
		$string .= '<br>';
		return $string;
		// Not working. Copy&Paste
	}










//
	function result_list($query = '', $keyword) {
		if ( $query->have_posts() ) { ?>
		<ul><?php
			while ( $query->have_posts() ) {
				$query->the_post();
				list_card();
			} ?>
		</ul><?php
		} else { ?>
			<ul>
				<li>
					<div class="max_wrap">
						<div class="no-events">
							<h2>No hay <?php echo $keyword; ?> en esta fecha.</h2>
						</div>
					</div>
				</li>
			</ul><?php
		}
		wp_reset_query();
	}
