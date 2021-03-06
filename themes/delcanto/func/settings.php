<?php

// Came from others

/*
 *	AGENDA: Get clean array from hours
 */

	function schedule_hours_array() {
		$hours = array();
		$hoursField = get_field('schedules');
		if($hoursField) {
			foreach($hoursField as $hour) {
				if($hour['hour']) {
					$hours[] = $hour['hour'];
				}
				if(!empty($hour['hour-start'])) {
					if($hour['hour-end']) {
						$hours[] = $hour['hour-start']. ' h. → '.$hour['hour-end'].' h.';
					} else {
						$hours[] = $hour['hour-start']. ' h.';
					}
				}
			}
		}
		return $hours;
	}


	function schedule_hours() {
		$allHours = schedule_hours_array();
		if(!empty($allHours)) {
			$string = rtrim(implode(', ', $allHours), ',');
		} elseif(empty($allHours)) {
			$string = false;
		} else {
			$string = 'Error';
		}
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
		if(!empty($chosenOne)) {$status = 'field: '.$chosenOne;} else {$status = 'field: ERROR';}

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
				$wdString = implode(', ', $weekdays);
			}}

			$status .= '[ start: '.$startDate.'. end: '.$endDate.'. wdays: '.$wdString.' ]';
			// loop per selected weekday
			if($weekdays) {
				$days = array();
				foreach($weekdays as $day) {
					// $days[] = $day;
					for($i = strtotime($row, $startDate); $i <= strtotime($endDate); $i = strtotime('+1 week', $i)) {
						$days[] = current_time('Ymd', $i);
					}
				}
			}

			$status .= implode(', ', $days);
			$result = $days;

		} else {
			$status .= '[ERROR]';
		}

		// return $status;
		return $result;
	}


	function schedule_days($format = 'F j Y', $showToday = false, $short = false) {
		$chosenOne = get_field('dates_options');
		$allDaysArray = schedule_days_array();
		if($chosenOne == 'dates') {
			if($allDaysArray) {
				foreach($allDaysArray as $row) {
					$originalDate = $row;
					$newDate = date_i18n($format, strtotime($originalDate));
					$newDate = ucfirst(strtolower($newDate));
					$today = current_time('Ymd');
					if($showToday == true AND $today == $originalDate) {
						$days[] = 'Hoy, '.$newDate;}
					else {
						$days[] = $newDate;
					}
				}
			}
			if($short == true) {
				$dayCount = count($days);
				if($dayCount == 1) {
					$string = rtrim(implode(', ', $days), ',');
				} else {
					$lastDay = end($days);
					$string = 'Hasta '.$lastDay;
					// $firstDay = $days[1]; WRONG: debe ser next date
					// $string = $firstDay.' </strong>('.$dayCount.' eventos más)<strong>'; // Anounces following dates
				}
			} else {
				$string = rtrim(implode(', ', $days), ',');
			}
		} else {
			if(have_rows('range_date_picker')) { while (have_rows('range_date_picker')) {
				the_row();
				$start_day = get_sub_field('start_day');
				$end_day = get_sub_field('end_day');
				$weekdays = get_sub_field('weekdays');
				$notes = get_sub_field('notes');
			}}
			if($short == false AND $weekdays) {
				$string = createRangeWeekdays($weekdays).'<br>';
			}
			$start_only_month = date_i18n('M', strtotime($start_day));
			$end_only_month = date_i18n('M', strtotime($end_day));
			if($start_only_month == $end_only_month) {
				$start_day = date_i18n('j', strtotime($start_day));
			} else {
				$start_day = date_i18n('j \d\e F', strtotime($start_day));
			}
			if($short == true) {
				$end_day = date_i18n('F d', strtotime($end_day));
				$end_day = ucfirst(strtolower($end_day));
				$string = 'Hasta '. $end_day;
			} else {
				$end_day = date_i18n('j \d\e F Y', strtotime($end_day));
				$string .= 'Del '. $start_day .' al '. $end_day;
				if($notes) {
					$string .= '<br>'.$notes;
				}
			}
		}

		// Check with today
		return $string;
	}





	function createRangeWeekdays($weekdays = false) {
		if($weekdays) {
			$wdString = implode(", ", $weekdays);
			$codex = array("mon", "tue", "wed", "thu", "fri", "sat", "sun");
			$nice = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
			$string = str_replace($codex, $nice, $wdString);
		} else {
			$string = 'error';
		}
		return $string;
	}


/*
 *	MOVIES: Make array from days & hours
 */

	function movieSchedule_array($format = 'Ymd') {
		$status = 'online';
		if(have_rows('dates_picker')){ while (have_rows('dates_picker')) { the_row();
			$day = date_i18n($format, strtotime(get_sub_field('day')));
			if(have_rows('schedules')) {
				$hours = array();
				while (have_rows('schedules')) { the_row();
				$hours[] = get_sub_field('hour').' h.';
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


	function mta_future_schedule($format = 'F j Y', $pt_labels = 'cineteca') {
		if(is_array($pt_labels)) {
		} elseif($pt_labels == 'cineteca') {
			$la_title = 'Próximas funciones';
			$la_no_results = 'Esta película no se ha programado nuevamente en cartelera.';
		} elseif($pt_labels == 'agenda') {
			$la_title = 'Fechas y Horarios';
			$la_no_results = 'Este evento ya concluyó.';
		} else {}
		$status = 'online';

		$string = '<dt class="label">'.$la_title.'</dt><dd>';
		$schedArray = mta_future_schedule_array('Ymd');
		if($schedArray) {
			foreach ($schedArray as $key) {
				$m = date_i18n('F', strtotime($key[0]));
				$nm = '';
				if($m != $nm) $month = ' <strong> de '.ucfirst($m).'</strong>';
				$niceday = date_i18n('l j', strtotime($key[0]));
				$string .= '<p>'.prefix_forDay($key[0], '', ', ');
				$string .= $niceday.$month.': ';
				// Add to Calendar buttons
				// $string .= implode(", ", $key[1]);
				// Uncomment for simple', 'hours.
				$hoursRaw = $key[1];
				$atcHours = array();
				foreach($hoursRaw AS $hour) {
					// $hoursTest [] =
					$hourSlug = str_replace(' h.', '',$hour);
					$hourSlug = date(" H:i:s", strtotime($hourSlug));
					$fullDate = $key[0].$hourSlug;
					$stDate = date_i18n('Y-m-d H:i:s', strtotime($fullDate));
					$enDate = date('Y-m-d H:i:s', strtotime("+2 hours", strtotime($fullDate)));
					$buttonbuild = '<span class="addtocalendar atc-style-orange" data-calendars="iCalendar, Google Calendar, Outlook Online, Outlook">';
					$buttonbuild .= '	<a class="atcb-link">'.$hour.'</a><var class="atc_event">';
					$buttonbuild .= '	<var class="atc_date_start">'.$stDate.'</var> <var class="atc_date_end">'.$enDate.'</var>';
					$buttonbuild .= '	<var class="atc_timezone">America/Mexico_City</var>';
					$buttonbuild .= '	<var class="atc_title">'.get_the_title().'</var>';
					$buttonbuild .= '	<var class="atc_description">'.get_the_excerpt().' –– '.get_the_permalink().'</var>';
					$buttonbuild .= '	<var class="atc_location">'.get_place_name().'</var>';
					$buttonbuild .= '</var></span>';
					$atcHours[] = $buttonbuild;
				}
				$boom = implode(' ', $atcHours);
				$string .= $boom;
				$string .= '</p>';

				$nm = date_i18n('F', strtotime($key[0]));
				$month = false;
			}
		} else {
			$string .= $la_no_results;
		}
		$string .= '</dd>';

		return $string;
		// print_r($stDays);
	}


	// function mta_future_schedule_addevent($hourArray) {
	// 	$string .= implode(", ", $hourArray);
	// 	return $string;
	// }


	function mta_future_schedule_array($format = 'Ymd') {
		$schedArray = movieSchedule_array($format);
		$today = current_time($format);
		$new = array_filter($schedArray, function ($var) use ($today) {
			return ($var[0] >= $today);
		});
		$new = array_values($new);
		return $new;
	}


	function mta_next_movie($format = 'F j Y', $pt_labels = 'cineteca') {
		$la_no_results = 'No disponible.';

		$schedArray = mta_future_schedule_array('Ymd');
		$schedArray = $schedArray[0];

		$m = date_i18n('F', strtotime($schedArray[0]));
		if($m != $nm) $month = ' <strong> de '.ucfirst($m).'</strong>';
		$niceday = date_i18n('j', strtotime($schedArray[0]));
		$string = prefix_forDay($schedArray[0], '', ', ');
		$string .= $niceday.$month.': ';
		$string .= implode(", ", $schedArray[1]);

		return $string;
	}


	function movieHoursClosestday($day = false, $plain = false) {
		$schedArray = movieSchedule_array('Ymd');
		if($day == false) $day = current_time('Ymd');
		if(have_rows('range_date_picker')) {
			$string = implode(', ', $schedArray[0][1]);
		} elseif($schedArray) {

			// 1 Look for $day
			$days_e = array();
			if($day) {
				foreach ($schedArray as $key => $val) {
					if($val[0] == $day) {
						$days_e[] = $val[1];
					} elseif($val[0] >= $day) {
						$new[] = $val[1];
					}
				}
			}
			$days_sep = implode(', ', $days_e[0]);
			$string = $days_sep;

		} else {
			$string = 'error.';
		}

		// DEBUG

		// RETURN
		return $string;
	}


	function prefix_forDay($any_day, $pre = '<strong>', $pos = '</strong> | ') {
		$today = current_time('Ymd');
		if($today == $any_day) $string = $pre.'Hoy'.$pos;
		if($today+1 == $any_day) $string = $pre.'Mañana'.$pos;
		return $string;
	}







/*
*	Generate option list for Select dropdown
*/

	function listSelOptions($term = false, $queried_term = false) {
		$terms = get_terms( array( 'taxonomy' => $term, 'parent' => 0 ) );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ foreach ( $terms as $term ) {
			$string .= '<option value="' . $term->slug . '"';
			if($term->slug == $queried_term) $string .= ' selected';
			$string .= '>' . $term->name . '</option>';
		}}
		return $string;
	}

	function sl_fix_pagination($link) {
	return str_replace('#038;', '&', $link);
	}
	add_filter('paginate_links', 'sl_fix_pagination');





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

	function get_skills() {
		$skillArray = get_field('skill_picker');
		if(is_array($skillArray) || is_object($skillArray)) {
			foreach ($skillArray as $skill) {
				$skillTerm = get_term( $skill, 'disciplinas' );
				$skills[] = $skillTerm->name;
			}
		}
		if($skills) {
			$string = implode(", ", $skills);
		}
		return $string;
	}











// List for search results
	function result_list($query = false, $keyword = false, $day = false) {
		echo $day.': day';
		echo 'query : '. $query;
		if ( $query->have_posts() ) { ?>
		<ul><?php
			while ( $query->have_posts() ) {
				$query->the_post();
				list_card($day);
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


	// Know weather to be an image or a Title
	function logo_or_title($hValue = 'h1') {
		$imgLogo = get_field('title_logo');
		if(!empty($imgLogo)) { ?>
			<img src="<?php echo $imgLogo['sizes']['large']; ?>" alt="<?php echo $imgLogo['alt']; ?>" class="event_logo" /><?php
		} else {
			echo '<'.$hValue.'>'.get_the_title().'</'.$hValue.'>';
		}
	}



	// Cleans out any string
	function mtn_cleanString($string = false) {
		$string = str_replace(' ', '-', $string);
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
	}
