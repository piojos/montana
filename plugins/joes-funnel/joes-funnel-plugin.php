<?php
/*
Plugin Name: Joes Funnel
Description: API Modifications for current requirements.
Author: Daniel Miranda
Version: 1.0
Author URI: http://raidho.mx
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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

	// more_info
	$keyval['price'] = $object['j_custom']['cost'];

	// rating ??? Movies
	// $keyval['rating'] = $object['j_custom']['rating'];

	// dates
	$keyval['dates'] = $object['j_custom']['everyday'];

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
			$keyval['audience'] = $meta['rating']['label'];
			$keyval['genre'] = $meta['genre']['label'];
			$keyval['year'] = $meta['year'];
			$keyval['director'] = $meta['director'];
			$keyval['cast'] = $meta['cast'];
			$keyval['runtime'] = $meta['length'];
			$keyval['rating'] = $meta['rating']['label']; // Movie rating: Does not exist!
		}
	}

	return $keyval;
}





// exposiciones

function joe_return_expos_talleres( $object, $field_name, $request ) {

	// Get all Basics
	$keyval = joe_return_basics( $object, $field_name, $request );

	// program
	$allWidgets = $object['j_custom']['widgets'];
	if(is_array($allWidgets) || is_object($allWidgets)) {
		foreach ($allWidgets as $widget) {
			if($widget['acf_fc_layout'] == 'list_type') {
				$createProgram = array( 'title' => $widget['title'], 'list' => $widget['list'] );
			}
		}
	}
	$keyval['program'] = $createProgram;

	return $keyval;
}
