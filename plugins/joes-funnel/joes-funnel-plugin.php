<?php
/*
Plugin Name: Joes Funnel
Description: API Modifications for current requirements.
Author: Daniel Miranda
Version: 1.0
Author URI: http://raidho.mx
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



add_action( 'rest_api_init', 'joes_register_api_hooks' );
function joes_register_api_hooks() {
	$allCPTs = array( 'agenda', 'cineteca', 'talleres', 'exposiciones' );

	// register_rest_field( $allCPTs,
	// 	'title',
	// 	array(
	// 		'get_callback'    => 'joe_return_title',
	// 	)
	// );
	//
	// register_rest_field( $allCPTs,
	// 	'category',
	// 	array(
	// 		'get_callback'    => 'joe_return_category',
	// 	)
	// );

	register_rest_field( $allCPTs,
		'description',
		array(
			'get_callback'    => 'joe_return_description',
		)
	);

	// register_rest_field( $allCPTs,
	// 	'place',
	// 	array(
	// 		'get_callback'    => 'joe_return_place',
	// 	)
	// );

	// register_rest_field( $allCPTs,
	// 	'more_info',
	// 	array(
	// 		'get_callback'    => 'joe_return_moreinfo',
	// 	)
	// );
	//
	// register_rest_field( $allCPTs,
	// 	'price',
	// 	array(
	// 		'get_callback'    => 'joe_return_price',
	// 	)
	// );

	// !! No existe en ningún CPT
	// register_rest_field( $allCPTs,
	// 	'rating',
	// 	array(
	// 		'get_callback'    => 'joe_return_rating',
	// 	)
	// );

	register_rest_field( $allCPTs,
		'dates',
		array(
			'get_callback'    => 'joe_return_dates',
		)
	);
	//
	// register_rest_field( $allCPTs,
	// 	'images',
	// 	array(
	// 		'get_callback'    => 'joe_return_images',
	// 	)
	// );
}




// function joe_return_title( $object, $field_name, $request ) {
// 	// return strip_tags( html_entity_decode( $object['content']['rendered'] ) );
// }

// function joe_return_category( $object, $field_name, $request ) {
// 	// return strip_tags( html_entity_decode( $object['content']['rendered'] ) );
// }

function joe_return_description( $object, $field_name, $request ) {
	return strip_tags( html_entity_decode( $object['content']['rendered'] ) );
}

// function joe_return_place( $object, $field_name, $request ) {
// 	// return strip_tags( html_entity_decode( $object['content']['rendered'] ) );
// }

// function joe_return_moreinfo( $object, $field_name, $request ) {
// 	// return strip_tags( html_entity_decode( $object['content']['rendered'] ) );
// }

// function joe_return_price( $object, $field_name, $request ) {
// 	// return strip_tags( html_entity_decode( $object['content']['rendered'] ) );
// }

// function joe_return_rating( $object, $field_name, $request ) {
// 	// return strip_tags( html_entity_decode( $object['content']['rendered'] ) );
// }

function joe_return_dates( $object, $field_name, $request ) {
	return strip_tags( html_entity_decode( $object['acf']['everyday'] ) );
}

// function joe_return_images( $object, $field_name, $request ) {
// 	// return strip_tags( html_entity_decode( $object['content']['rendered'] ) );
// }
