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

	function alt_rangeSchedule($object) {
		$format = 'Y-m-d H:i:s';

		$dates_repeater = get_field('range_date_picker' );
		$stday = $dates_repeater[0]['start_day'];
		$sthour = $dates_repeater[0]['hour'];
		$enday = $dates_repeater[0]['end_day'];


		// $stday = $object['j_custom']['range_date_picker'][0]['start_day'];
		// $sthour = $object['j_custom']['schedules'][0]['hour'];
		// $enday = $object['j_custom']['range_date_picker'][0]['end_day'];

		$hourlater = strtotime($sthour) + 60*60;
		$strHour = date('H:i:s', strtotime($sthour));
		$endHour = date('H:i:s', $hourlater);
		$stDate = date_i18n($format, strtotime($stday.$strHour));
		$enDate = date_i18n($format, strtotime($enday.$endHour));

		$daysList = array(
			'start' => $stDate.'a',
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


function slug_get_acf( $object = false, $field_name = false, $request = false ) {
	return get_fields($object[ 'id' ]);
}

function jf_costlist($costgroup = FALSE) {
	if(is_array($costgroup)) {
		$list = array();
		foreach($costgroup as $group) {
			$list[] = array(
				'concept' => $group['group'],
				'cost' => $group['cost']
			);
		}
	} else {
		$list .= 'somethin else';
	}
	return $list;
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


	register_rest_field( 'servicios',
		'services',
		array(
			'get_callback'    => 'joe_return_basics',
		)
	);
}




function joe_return_basics( $object, $field_name, $request ) {

	// id
	$keyval = array('id' => $object['id']);

	// Order day
	$order_day = $object['j_custom']['order_day'];
	if($order_day) {
		$keyval['order_num'] = $order_day;
	}

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
		// foreach($costs as $cost) {
		// 	$keyval['cost'] = '$'.$cost['cost'];
		// 	$keyval['concept'] = $cost['group'];
		// }
		$costlist = $object['j_custom']['cost_groups'];
		$keyval['price'] = jf_costlist($costlist);
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
	} elseif($object['type'] == 'exposiciones' || $object['type'] == 'talleres' || $object['type'] == 'servicios') {
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





// Comments & Ratings

add_action( 'rest_api_init', 'myplugin_add_karma' );
function myplugin_add_karma() {
	register_rest_field( 'comment', 'rating', array(
		'get_callback' => function( $comment_arr ) {
		$comment_obj = get_comment( $comment_arr['id'] );
		return (int) $comment_obj->comment_karma;
		},
		'update_callback' => function( $karma, $comment_obj ) {
		$ret = wp_update_comment( array(
		'comment_ID'    => $comment_obj->comment_ID,
		'comment_karma' => $karma
		) );
		if ( false === $ret ) {
			return new WP_Error(
				'rest_comment_karma_failed',
				__( 'Failed to update comment karma.' ),
				array( 'status' => 500 )
			);
		}
			return true;
		},
		'schema' => array(
			'description' => __( 'Comment karma.' ),
			'type'        => 'integer'
		),
	) );
}

function show_message_function( $comment_ID, $comment_approved ) {
	if( 1 === $comment_approved ){
		$gets_comment = get_comment( $comment_ID );
		$comment_post_id = $gets_comment->comment_post_ID ;
		update_field( 'public_rating', 'alo', $comment_post_id );
	}
}
add_action( 'comment_post', 'show_message_function', 10, 2 );



// Allow comments from POST
function filter_rest_allow_anonymous_comments() {
	return true;
}
add_filter('rest_allow_anonymous_comments','filter_rest_allow_anonymous_comments');






// Route para Agenda
add_action( 'rest_api_init', 'jf_register_api_hooks_f' );
function jf_register_api_hooks_f() {
	register_rest_route( 'apiconarte/v1', '/agenda/', array(
		'methods' => 'GET',
		'callback' => 'jf_get_age',
	) );

	register_rest_route( 'apiconarte/v1', '/cineteca/', array(
		'methods' => 'GET',
		'callback' => 'jf_get_cin',
	) );

	register_rest_route( 'apiconarte/v1', '/exposiciones/', array(
		'methods' => 'GET',
		'callback' => 'jf_get_exp',
	) );

	register_rest_route( 'apiconarte/v1', '/talleres/', array(
		'methods' => 'GET',
		'callback' => 'jf_get_tal',
	) );

	register_rest_route( 'apiconarte/v1', '/servicios/', array(
		'methods' => 'GET',
		'callback' => 'jf_get_ser',
	) );
}

function jf_get_age() {

	$today = current_time('Ymd0000');

	$args = array(
		'post_type'		=> 'agenda',
		'posts_per_page'	=> -1,
		'meta_query' => array (
			array(
				'key'       => 'order_day',
				'value'     => $today,
				'compare'   => '>',
			)
		),
		'meta_key'		=> 'order_day',
		'orderby'		=> 'meta_value_num',
		'order'			=> 'ASC',
	);

	$filter = new WP_Query( $args );
	if ( $filter->have_posts() ) : while ( $filter->have_posts() ) : $filter->the_post();
		$img_id = get_post_thumbnail_id();
		$img_url = wp_get_attachment_image_src( $img_id, 'medium' );

		$costOptions = get_field('cost_options');
		if( $costOptions && in_array('free', $costOptions) ) {
			$finalCost = 'Entrada libre';
		} else {
			$finalCost = get_field('cost_groups');
		}
		$costOptions = get_field('cost_options');

		$all_post_ids[] = array(
			'id' => get_the_id(),
			'title' => get_the_title(),
			'link' => get_the_permalink(),
			'category' => get_skills(),
			'description' => get_the_content(),
			'place' => get_place_name(),
			'prices' => $finalCost,
			'dates' => jf_datesSchedule_array(),
			'images' => $img_url[0],
			'order_day' => get_field('order_day'),
		);
	endwhile; wp_reset_postdata(); endif;

	return $all_post_ids;

}





function jf_get_cin() {

	$today = current_time('Ymd0000');

	$args = array(
		'post_type'		=> 'cineteca',
		'posts_per_page'	=> -1,
		'meta_query' => array (
			array(
				'key'       => 'order_day',
				'value'     => $today,
				'compare'   => '>',
			)
		),
		'meta_key'		=> 'order_day',
		'orderby'		=> 'meta_value_num',
		'order'			=> 'ASC',
	);

	$filter = new WP_Query( $args );
	if ( $filter->have_posts() ) : while ( $filter->have_posts() ) : $filter->the_post();
		$img_id = get_post_thumbnail_id();
		$img_url = wp_get_attachment_image_src( $img_id, 'medium' );

		$costOptions = get_field('cost_options');
		if( $costOptions && in_array('free', $costOptions) ) {
			$finalCost = 'Entrada libre';
		} else {
			$finalCost = get_field('cost_groups');
		}

		// if(have_rows('meta')){
		// 	while (have_rows('meta')) {
		// 		the_row();
		// 		$mYear = get_sub_field('year');
		// 		$mCountry = get_sub_field('countries');
		// 		$mDirector = get_sub_field('director');
		// 		$mGenre = get_sub_field('genre');
		// 		$mLength = get_sub_field('length');
		// 		$mAudience = get_sub_field('rating');
		// 	}
		// }

		$meta_repeater = get_field('meta');
		$meta = $meta_repeater[0];

		$all_post_ids[] = array(
			'id' => get_the_id(),
			'title' => get_the_title(),
			'link' => get_the_permalink(),
			'category' => get_skills(),
			'description' => get_the_content(),
			'place' => get_place_name(),
			'prices' => $finalCost,
			'dates' => jf_datesSchedule_array(),
			'images' => $img_url[0],
			'meta' => $meta,
			// if($mAudience) 'audience' = $mAudience;
			// if($mGenre) 'genre' = $mGenre;
			// if($mYear) 'year' = $mYear;
			// if($mDirector) 'director' = $mDirector;
			// if($mCast) 'cast' = $mCast;
			// if($mLength) 'runtime' = $mLength;
			'order_day' => get_field('order_day'),
		);

	endwhile; wp_reset_postdata(); endif;

	return $all_post_ids;

}




function jf_get_exp() {

	$today = current_time('Ymd0000');
	$thisMonthStart = date('Y-m-d', strtotime($today));
	$nextMonth = date('Y-m-d', strtotime("+1 month", strtotime($today)));
	$thisMonthEnd = date('Y-m-d', strtotime("-1 day", strtotime($nextMonth)));

	$args = array(
		'post_type'		=> 'exposiciones',
		'posts_per_page'	=> -1,
		'meta_query' => array(
			'relation'		=> 'AND',
			array(
				'key'		=> 'range_date_picker_0_start_day',
				'compare'	=> '<=',
				'value'		=> $thisMonthEnd,
				'type'		=> 'DATE'
			),
			array(
				'key'		=> 'range_date_picker_0_end_day',
				'compare'	=> '>=',
				'value'		=> $thisMonthStart,
				'type'		=> 'DATE'
			)
		),
	);

	$filter = new WP_Query( $args );

	if ( $filter->have_posts() ) : while ( $filter->have_posts() ) : $filter->the_post();
		$img_id = get_post_thumbnail_id();
		$img_url = wp_get_attachment_image_src( $img_id, 'medium' );

		$costOptions = get_field('cost_options');
		if( $costOptions && in_array('free', $costOptions) ) {
			$finalCost = 'Entrada libre';
		} else {
			$finalCost = get_field('cost_groups');
		}
		$costOptions = get_field('cost_options');

		$dates_repeater = get_field('range_date_picker' );

		$all_post_ids[] = array(
			'id' => get_the_id(),
			'title' => get_the_title(),
			'link' => get_the_permalink(),
			'category' => get_skills(),
			'description' => get_the_content(),
			'place' => get_place_name(),
			'prices' => $finalCost,
			'dates' => $dates_repeater,
			'images' => $img_url[0],
			'order_day' => get_field('order_day'),
		);
	endwhile; wp_reset_postdata(); endif;

	return $all_post_ids;

}







function jf_get_tal() {

	$today = current_time('Ym\0\1\0\0\0\0');

	$args = array(
		'post_type'		=> 'talleres',
		'posts_per_page'	=> -1,
		'meta_query' => array (
			array(
				'key'       => 'range_date_picker_0_end_day',
				'value'     => $today,
				'compare'   => '>=',
			)
		),
		'meta_key'		=> 'order_day',
		'orderby'		=> 'meta_value_num',
		'order'			=> 'ASC',
	);

	$filter = new WP_Query( $args );
	if ( $filter->have_posts() ) : while ( $filter->have_posts() ) : $filter->the_post();
		$img_id = get_post_thumbnail_id();
		$img_url = wp_get_attachment_image_src( $img_id, 'medium' );

		$costOptions = get_field('cost_options');
		if( $costOptions && in_array('free', $costOptions) ) {
			$finalCost = 'Entrada libre';
		} else {
			$finalCost = get_field('cost_groups');
		}
		$costOptions = get_field('cost_options');

		$all_post_ids[] = array(
			'id' => get_the_id(),
			'title' => get_the_title(),
			'link' => get_the_permalink(),
			'category' => get_skills(),
			'description' => get_the_content(),
			'place' => get_place_name(),
			'prices' => $finalCost,
			'dates' => alt_rangeSchedule(),
			'images' => $img_url[0],
			'order_day' => get_field('order_day'),
		);
	endwhile; wp_reset_postdata(); endif;

	return $all_post_ids;

}







function jf_get_ser() {

	$args = array(
		'post_type'		=> 'servicios',
		'posts_per_page'	=> -1,
	);

	$filter = new WP_Query( $args );
	if ( $filter->have_posts() ) : while ( $filter->have_posts() ) : $filter->the_post();
		$img_id = get_post_thumbnail_id();
		$img_url = wp_get_attachment_image_src( $img_id, 'medium' );

		$costOptions = get_field('cost_options');
		if( $costOptions && in_array('free', $costOptions) ) {
			$finalCost = 'Entrada libre';
		} else {
			$finalCost = get_field('cost_groups');
		}
		$costOptions = get_field('cost_options');

		$dates_repeater = get_field('range_date_picker' );
		$date = $dates_repeater[0]['notes'];

		$all_post_ids[] = array(
			'id' => get_the_id(),
			'title' => get_the_title(),
			'link' => get_the_permalink(),
			'category' => get_skills(),
			'description' => get_the_content(),
			'place' => get_place_name(),
			'prices' => $finalCost,
			'dates' => $date,
			'images' => $img_url[0],
			'order_day' => get_field('order_day'),
		);
	endwhile; wp_reset_postdata(); endif;

	return $all_post_ids;

}
