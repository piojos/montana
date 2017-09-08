<?php
/*
Plugin Name: Joes Funnel
Description: API Modifications for current requirements.
Author: Daniel Miranda
Version: 1.0
Author URI: http://raidho.mx
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Route para Agenda
add_action( 'rest_api_init', 'jf_register_api_hooks' );
function jf_register_api_hooks() {
	register_rest_route( 'masconarte/v1', '/current_agenda/', array(
		'methods' => 'GET',
		'callback' => 'jf_get_current_agenda',
	) );
}
function jf_get_current_agenda() {
	$currentAgenda = get_field('monthly_agenda', 'options');
	$all_post_ids = array('url' => $currentAgenda);
	return $all_post_ids;
}

// Get dates array
	function jf_datesSchedule_array() {
		$format = 'Y-m-d H:i:s';
		$sched_array = movieSchedule_array();
		$daysList = array();
		foreach($sched_array as $day) {
			$jfDS_day = $day[0];
			foreach($day[1] as $hour) {
				$hourlater = strtotime($hour) + 60*60;
				$strHour = date('H:i:s', strtotime($hour));
				$endHour = date('H:i:s', $hourlater);
				$stDate = date_i18n($format, strtotime($jfDS_day.$strHour));
				$enDate = date_i18n($format, strtotime($jfDS_day.$endHour));
				$daysList[] = array(
					// Debugging
					// 'hour' => $hour,
					// 'hourlater' => $hourlater,
					// 's-h' => $strHour,
					// 'e-h' => $endHour,
					'start' => $stDate,
					'end' => $enDate
				);
			}
		}
		return $daysList;
	}


// Get range
	function jf_rangeSchedule($object) {
		$format = 'Y-m-d H:i:s';

		$stday = $object['j_custom']['range_date_picker'][0]['start_day'];
		$sthour = $object['j_custom']['schedules'][0]['hour'];
		$enday = $object['j_custom']['range_date_picker'][0]['end_day'];

		$hourlater = strtotime($sthour) + 60*60;
		$strHour = date('H:i:s', strtotime($sthour));
		$endHour = date('H:i:s', $hourlater);
		$stDate = date_i18n($format, strtotime($stday.$strHour));
		$enDate = date_i18n($format, strtotime($enday.$endHour));

		$daysList = array(
			'start' => $stDate,
			'end' => $enDate
		);
		return $daysList;
	}




add_action( 'rest_api_init', 'slug_register_acf' );
function slug_register_acf() {
	$post_types = get_post_types(['public'=>true], 'names');
	foreach ($post_types as $type) {
		register_api_field (
			$type,
			'j_custom',
			array(
				'get_callback'    => 'slug_get_acf',
				'update_callback' => null,
				'schema'          => null,
			)
		);
	}
}
function slug_get_acf( $object, $field_name, $request ) {
	return get_fields($object[ 'id' ]);
}



add_action( 'rest_api_init', 'joes_register_api_hooks');
function joes_register_api_hooks() {
	// $allCPTs = array( 'agenda', 'cineteca', 'talleres', 'exposiciones' );

	register_rest_field( 'agenda',
		'event',
		array(
			'get_callback'    => 'joe_return_basics',
		)
	);


	register_rest_field( 'cineteca',
		'cinema',
		array(
			'get_callback'    => 'joe_return_movie',
		)
	);


	register_rest_field( 'exposiciones',
		'exposition',
		array(
			'get_callback'    => 'joe_return_expos_talleres',
		)
	);


	register_rest_field( 'talleres',
		'workshop',
		array(
			'get_callback'    => 'joe_return_expos_talleres',
		)
	);
}




function joe_return_basics( $object, $field_name, $request ) {

	// id
	$keyval = array('id' => $object['id']);

	// title
	$title = strip_tags( html_entity_decode( $object['title']['rendered'] ) );
	$keyval['title'] = $title;

	// skills
	$skillID = $object['j_custom']['skill_picker'];
	$skillArray = array();
	if(is_array($skillID) || is_object($skillID)) {
		foreach ($skillID as $skill) {
			$skillObject = get_term_by('term_id', $skill, 'disciplinas');
			$skillArray[] = $skillObject->name;
		}
	}
	$keyval['category'] = $skillArray;

	// description
	$description = strip_tags( html_entity_decode( $object['content']['rendered'] ) );
	$keyval['description'] = $description;

	// place
	$placeID = $object['j_custom']['location_picker'];
	$thisPlace = get_term_by('term_id', $placeID, 'lugares');
	$keyval['place'] = $thisPlace->name;

	// time
	$keyval['time'] = schedule_hours();

	// more_info
	$keyval['more_info'] = $object['j_custom']['cost_message'];

	// Cost
	$checkCost = $object['j_custom']['cost_options'];
	if($checkCost) {
		if(in_array('free', $checkCost)) {
			$keyval['price'] = 'free';
		} else {
			$keyval['price'] = '$'.$object['j_custom']['cost'];
		}
	} else {
		// No 'cost_options'
		// $keyval['price'] = '$'.$object['j_custom']['cost'];
	}

	// rating ??? Movies
	// $keyval['rating'] = $object['j_custom']['rating'];

	// dates
	$tempchoose = $object['j_custom']['dates_options'];
	// $keyval['dates_choose'] = $tempchoose;
	if(!empty($tempchoose)) {
		if($tempchoose == 'dates') {
			$keyval['dates'] = jf_datesSchedule_array();
		} elseif($tempchoose == 'range') {
			$keyval['dates'] = jf_rangeSchedule($object);
		}
	} elseif($object['type'] == 'cineteca') {
		$keyval['dates'] = jf_datesSchedule_array();
	} elseif($object['type'] == 'exposiciones' || $object['type'] == 'talleres') {
		$keyval['dates'] = jf_rangeSchedule($object);
	} else {
		$keyval['dates'] = 'no $tempchoose (not an event).';
	}
	// images
	$keyval['images'] = $object['better_featured_image']['source_url'];

	// Contact
	// $keyval['contact'] = $object['j_custom']['meta']['contacto'];

	return $keyval;
}







// Movie Fields

function joe_return_movie( $object, $field_name, $request ) {

	$keyval = joe_return_basics( $object, $field_name, $request );

	// program
	$allMeta = $object['j_custom']['meta'];
	if(is_array($allMeta) || is_object($allMeta)) {
		foreach ($allMeta as $meta) {
			$m_aud = $meta['rating']['label'];
			$m_gen = $meta['genre']['label'];
			$m_yea = $meta['year'];
			$m_dir = $meta['director'];
			$m_cas = $meta['cast'];
			$m_run = $meta['length'];
			// $keyval['rating'] = $meta['rating']['label']; // Movie rating: Does not exist!

			if($m_aud) $keyval['audience'] = $m_aud;
			if($m_gen) $keyval['genre'] = $m_gen;
			if($m_yea) $keyval['year'] = $m_yea;
			if($m_dir) $keyval['director'] = $m_dir;
			if($m_cas) $keyval['cast'] = $m_cas;
			if($m_run) $keyval['runtime'] = $m_run;
		}
	}

	return $keyval;
}





// exposiciones

function joe_return_expos_talleres( $object, $field_name, $request ) {

	// Get all Basics
	$keyval = joe_return_basics( $object, $field_name, $request );

	// Presentor
	$checkPresentor = $object['j_custom']['presentor'];
	if($checkPresentor) {
		$keyval['imparte'] = $checkPresentor;
	}

	// Program
	$allWidgets = $object['j_custom']['widgets'];
	if(is_array($allWidgets) || is_object($allWidgets)) {
		foreach ($allWidgets as $widget) {
			if($widget['acf_fc_layout'] == 'list_type') {
				$createProgram = array( 'title' => $widget['title'], 'list' => $widget['list'] );
			}
		}
	}
	if($createProgram) {
		$keyval['program'] = $createProgram;
	}

	return $keyval;
}


// Allow comments from POST
function filter_rest_allow_anonymous_comments() {
	return true;
}
add_filter('rest_allow_anonymous_comments','filter_rest_allow_anonymous_comments');
