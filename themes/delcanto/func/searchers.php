<?php

/*
 * 	SEARCHERS

 * 	Handles variations on.

 * 	1 Functions

 * 	2 Agenda
 * 	3 Cineteca
 * 	4 Exposiciones
 * 	5 Talleres
 * 	6 Convocatorias

 * 	7 Search Page
 */

	// Day Controls: Generates days
	function adc_searcher_day_item($day = '', $class = '') {
		$today = current_time('Ymd');
		$dc_format = 'D \<\s\t\r\o\n\g\> j \<\/\s\t\r\o\n\g\>';
		if( $day < $today ) {
			$class .= ' past';
		} elseif( $day == $today ) {
			$class .= ' today';
		}
		$class = ' class="input wrap radio '.$class.'"';
		$string = '<div'. $class .'><input type="radio" name="fecha" value="'. $day .'" id="qf_'. $day .'" onClick="document.getElementById(\'visibleFecha\').value=this.value"><label for="qf_'. $day .'">'. date_i18n( $dc_format, strtotime( $day ) ) .'</label></div>';
		// $string = '<button type="submit" name="fecha" value="'. $day .'"'.$class.'>'. date_i18n( $dc_format, strtotime( $day ) ) .'</button>';
		return $string;
	}

	// Day Controls: Generates days. USED IN EXPOSICIONES
	function adc_searcher_month_item($day = '', $class = '') {
		$today = current_time('Ym\0\1');
		$month = date('m-d', strtotime($day));

		// Add year on January 1
		if($month == '01-01') {
			$dc_format = 'F \<\s\p\a\n\> Y \<\/\s\p\a\n\>';
		} else {
			$dc_format = 'F';
		}

		if( $day < $today ) {
			$class .= ' past';
		} elseif( $day == $today ) {
			$class .= ' today';
		}
		$class = ' class="input wrap radio '.$class.'"';
		$string = '<div'. $class .'><input type="radio" name="fecha" value="'. $day .'" id="qf_'. $day .'" onClick="document.getElementById(\'visibleFecha\').value=this.value"><label for="qf_'. $day .'">'. date_i18n( $dc_format, strtotime( $day ) ) .'</label></div>';
		return $string;
	}
