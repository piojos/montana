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


// function jf_get_current_agenda() {
// 	// $currentAgenda = get_field('monthly_agenda', 'options');
// 	$ag_date = 'a';
// 	if( have_rows('section_blocks', 6) ):
// 		$ag_date .= 'b';
//
// 		while ( have_rows('section_blocks', 6) ) : the_row();
// 			$ag_date .= 'c';
//
// 		elseif( get_row_layout() == 'block_search' ):
// 			if( have_rows('current_agenda') ):
// 				while ( have_rows('current_agenda') ) : the_row();
// 					$f_m = get_sub_field('month');
// 					$f_y = get_sub_field('year');
// 					$ag_file = get_sub_field('file_url');
// 				endwhile;
// 				else :
// 			endif;
//
// 		endif;
//
// 		endwhile;
// 	else :
// 	endif;
//
// 	$ag_date = $f_m.', '.$f_y;
//
// 	$all_post_ids = array(
// 		'date' => $ag_date,
// 		'url' => $ag_file);
// 	return $all_post_ids;
// }


// Get dates array
	function jf_datesSchedule_array() {
		$format = 'Y-m-d H:i:s';
		$sched_array = movieSchedule_array();
		$daysList = array();
		foreach($sched_array as $day) {
			$jfDS_day = $day[0];
			foreach($day[1] as $hour) {
				$hour = str_replace(' h.', ':00', $hour);
				$hourlater = strtotime($hour) + 60*60;
				$strHour = date('H:i:s', strtotime($hour));
				$endHour = date('H:i:s', $hourlater);
				$stDate = date($format, strtotime($jfDS_day.$strHour));
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
			'start' => $stDate,
			'end' => $enDate
		);
		return $daysList;
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
		$list .= '_';
	}
	return $list;
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
		// 'args' => array(
		// 	'page' => array(
		// 		'validate_callback' => function($param, $request, $key) {
		// 			return is_numeric( $param );
		// 		}
		// 	),
		// ),
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






function jf_get_age( WP_REST_Request $request ) {

	$today = current_time('Ymd0000');


	$today_min = current_time('Ymd');
	$qd1 = date('Ymd', strtotime("+1 day", strtotime($today_min)));
	$qd2 = date('Ymd', strtotime("+2 day", strtotime($today_min)));
	$qd3 = date('Ymd', strtotime("+3 day", strtotime($today_min)));
	$qd4 = date('Ymd', strtotime("+4 day", strtotime($today_min)));
	$qd5 = date('Ymd', strtotime("+5 day", strtotime($today_min)));
	$qd6 = date('Ymd', strtotime("+6 day", strtotime($today_min)));
	$qd7 = date('Ymd', strtotime("+7 day", strtotime($today_min)));
	$qd8 = date('Ymd', strtotime("+8 day", strtotime($today_min)));
	$qd9 = date('Ymd', strtotime("+9 day", strtotime($today_min)));
	$qd10 = date('Ymd', strtotime("+10 day", strtotime($today_min)));
	$qd11 = date('Ymd', strtotime("+11 day", strtotime($today_min)));
	$qd12 = date('Ymd', strtotime("+12 day", strtotime($today_min)));
	$qd13 = date('Ymd', strtotime("+13 day", strtotime($today_min)));
	$qd14 = date('Ymd', strtotime("+14 day", strtotime($today_min)));


	if($request['page']) {
		$page_num = $request['page'];
	} else {
		$page_num = 1;
	}
	$args = array(
		'post_type'		=> 'agenda',
		'meta_query' => array (
			'relation' => 'OR',
			array(
				'key'       => 'order_day',
				'value'     => $today,
				'compare'   => '>',
			),
			array(
				'key' => 'everyday', 'value' => $qd1, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd2, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd3, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd4, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd5, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd6, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd7, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd8, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd9, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd10, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd11, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd12, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd13, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd14, 'compare' => 'LIKE'
			)
		),
		'posts_per_page'	=> 40,
		'paged'				=> $page_num,
		'meta_key'		=> 'order_day',
		'orderby'		=> 'meta_value_num',
		'order'			=> 'ASC',
	);


	$filter = new WP_Query( $args );

	$all_post_ids[] = array(
		'requested_page' => $page_num,
		'total_results' => $filter->post_count
	);


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

		$post_ID = get_the_id();

		$comments = get_comments(array('post_id' => $post_ID));
		$countcomments = count($comments);
		$single_rate = array();

		foreach($comments as $comment) {
			$single_rate[] = $comment->comment_karma;
		}

		if($countcomments == 0) {
			$rating_status = 'Not Rated';
		} else {
			$rating_status = array_sum($single_rate)/count($single_rate);
		}


		$place_id = get_field('location_picker');
		if( $costOptions && in_array('showcontact', $costOptions) ) {
			if(in_array('overridecontact', $costOptions)) {
				$contact_status = get_field('contact');
			} elseif(!empty($place_id)) {
				$contact_status = get_field('contact', 'lugares_'.$place_id);
			} else {
				$contact_status = 'No contact';
			}
		}

		$all_post_ids[] = array(
			'id' => $post_ID,
			'title' => get_the_title(),
			'link' => get_the_permalink(),
			'category' => get_skills(),
			'description' => get_the_content(),
			'place' => get_place_name(),
			'prices' => $finalCost,
			'dates' => jf_datesSchedule_array(),
			'images' => $img_url[0],
			'rating' => $rating_status,
			'contact' => $contact_status,
			'order_day' => get_field('order_day'),
		);
	endwhile; wp_reset_postdata(); endif;

	return $all_post_ids;

}





function jf_get_cin( WP_REST_Request $request ) {

	$today = current_time('Ymd0000');
	$today_min = current_time('Ymd');
	$qd1 = date('Ymd', strtotime("+1 day", strtotime($today_min)));
	$qd2 = date('Ymd', strtotime("+2 day", strtotime($today_min)));
	$qd3 = date('Ymd', strtotime("+3 day", strtotime($today_min)));
	$qd4 = date('Ymd', strtotime("+4 day", strtotime($today_min)));
	$qd5 = date('Ymd', strtotime("+5 day", strtotime($today_min)));
	$qd6 = date('Ymd', strtotime("+6 day", strtotime($today_min)));
	$qd7 = date('Ymd', strtotime("+7 day", strtotime($today_min)));
	$qd8 = date('Ymd', strtotime("+8 day", strtotime($today_min)));
	$qd9 = date('Ymd', strtotime("+9 day", strtotime($today_min)));
	$qd10 = date('Ymd', strtotime("+10 day", strtotime($today_min)));
	$qd11 = date('Ymd', strtotime("+11 day", strtotime($today_min)));
	$qd12 = date('Ymd', strtotime("+12 day", strtotime($today_min)));
	$qd13 = date('Ymd', strtotime("+13 day", strtotime($today_min)));
	$qd14 = date('Ymd', strtotime("+14 day", strtotime($today_min)));

	if($request['page']) {
		$page_num = $request['page'];
	} else {
		$page_num = 1;
	}

	$args = array(
		'post_type'		=> 'cineteca',
		'meta_query' => array (
			'relation' => 'OR',
			array(
				'key'       => 'order_day',
				'value'     => $today,
				'compare'   => '>',
			),
			array(
				'key' => 'everyday', 'value' => $qd1, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd2, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd3, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd4, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd5, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd6, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd7, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd8, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd9, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd10, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd11, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd12, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd13, 'compare' => 'LIKE'
			),
			array(
				'key' => 'everyday', 'value' => $qd14, 'compare' => 'LIKE'
			)
		),
		'posts_per_page'	=> 40,
		'paged'				=> $page_num,
		'meta_key'		=> 'order_day',
		'orderby'		=> 'meta_value_num',
		'order'			=> 'ASC',
	);


	$filter = new WP_Query( $args );

	$all_post_ids[] = array(
		'page_request' => $page_num,
		'total_results' => $filter->post_count
	);

	if ( $filter->have_posts() ) : while ( $filter->have_posts() ) : $filter->the_post();
		$img_id = get_post_thumbnail_id();
		$img_url = wp_get_attachment_image_src( $img_id, 'medium' );

		$costOptions = get_field('cost_options');
		if( $costOptions && in_array('free', $costOptions) ) {
			$finalCost = 'Entrada libre';
		} else {
			$finalCost = get_field('cost_groups');
		}

		$meta_repeater = get_field('meta');
		$meta = $meta_repeater[0];


		$post_ID = get_the_id();

		$comments = get_comments(array('post_id' => $post_ID));
		$countcomments = count($comments);
		$single_rate = array();

		foreach($comments as $comment) {
			$single_rate[] = $comment->comment_karma;
		}

		if($countcomments == 0) {
			$rating_status = 'Not Rated';
		} else {
			$rating_status = array_sum($single_rate)/count($single_rate);
		}


		$place_id = get_field('location_picker');
		if( $costOptions && in_array('showcontact', $costOptions) ) {
			if(in_array('overridecontact', $costOptions)) {
				$contact_status = get_field('contact');
			} elseif(!empty($place_id)) {
				$contact_status = get_field('contact', 'lugares_'.$place_id);
			} else {
				$contact_status = 'No contact';
			}
		}


		$all_post_ids[] = array(
			'id' => $post_ID,
			'title' => get_the_title(),
			'link' => get_the_permalink(),
			'description' => get_the_content(),
			'place' => get_place_name(),
			'prices' => $finalCost,
			'dates' => jf_datesSchedule_array(),
			'images' => $img_url[0],
			'meta' => $meta,
			'rating' => $rating_status,
			'contact' => $contact_status,
			'order_day' => get_field('order_day'),
		);

	endwhile; wp_reset_postdata(); endif;

	return $all_post_ids;

}




function jf_get_exp( WP_REST_Request $request ) {

	$today = current_time('Ymd0000');
	$thisMonthStart = date('Y-m-d', strtotime($today));
	$nextMonth = date('Y-m-d', strtotime("+1 month", strtotime($today)));
	$thisMonthEnd = date('Y-m-d', strtotime("-1 day", strtotime($nextMonth)));

	if($request['page']) {
		$page_num = $request['page'];
	} else {
		$page_num = 1;
	}

	$args = array(
		'post_type'		=> 'exposiciones',
		'posts_per_page'	=> 40,
		'paged'				=> $page_num,
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

	$all_post_ids[] = array(
		'page_request' => $page_num,
		'total_results' => $filter->post_count
	);

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

		$post_ID = get_the_id();

		$comments = get_comments(array('post_id' => $post_ID));
		$countcomments = count($comments);
		$single_rate = array();

		foreach($comments as $comment) {
			$single_rate[] = $comment->comment_karma;
		}

		if($countcomments == 0) {
			$rating_status = 'Not Rated';
		} else {
			$rating_status = array_sum($single_rate)/count($single_rate);
		}


		$place_id = get_field('location_picker');
		if( $costOptions && in_array('showcontact', $costOptions) ) {
			if(in_array('overridecontact', $costOptions)) {
				$contact_status = get_field('contact');
			} elseif(!empty($place_id)) {
				$contact_status = get_field('contact', 'lugares_'.$place_id);
			} else {
				$contact_status = 'No contact';
			}
		}


		$all_post_ids[] = array(
			'id' => $post_ID,
			'title' => get_the_title(),
			'link' => get_the_permalink(),
			'category' => get_skills(),
			'description' => get_the_content(),
			'place' => get_place_name(),
			'prices' => $finalCost,
			'dates' => $dates_repeater,
			'images' => $img_url[0],
			'rating' => $rating_status,
			'contact' => $contact_status,
			'order_day' => get_field('order_day'),
		);
	endwhile; wp_reset_postdata(); endif;

	return $all_post_ids;

}







function jf_get_tal( WP_REST_Request $request ) {

	$today = current_time('Ymd0000');

	if($request['page']) {
		$page_num = $request['page'];
	} else {
		$page_num = 1;
	}

	$args = array(
		'post_type'		=> 'talleres',
		'posts_per_page'	=> 40,
		'paged'				=> $page_num,
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

	$all_post_ids[] = array(
		'page_request' => $page_num,
		'total_results' => $filter->post_count
	);

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

		$post_ID = get_the_id();

		$comments = get_comments(array('post_id' => $post_ID));
		$countcomments = count($comments);
		$single_rate = array();

		foreach($comments as $comment) {
			$single_rate[] = $comment->comment_karma;
		}

		if($countcomments == 0) {
			$rating_status = 'Not Rated';
		} else {
			$rating_status = array_sum($single_rate)/count($single_rate);
		}


		$place_id = get_field('location_picker');
		if( $costOptions && in_array('showcontact', $costOptions) ) {
			if(in_array('overridecontact', $costOptions)) {
				$contact_status = get_field('contact');
			} elseif(!empty($place_id)) {
				$contact_status = get_field('contact', 'lugares_'.$place_id);
			} else {
				$contact_status = 'No contact';
			}
		}


		$all_post_ids[] = array(
			'id' => $post_ID,
			'title' => get_the_title(),
			'link' => get_the_permalink(),
			'category' => get_skills(),
			'description' => get_the_content(),
			'place' => get_place_name(),
			'prices' => $finalCost,
			'dates' => alt_rangeSchedule(),
			'images' => $img_url[0],
			'rating' => $rating_status,
			'contact' => $contact_status,
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

		$post_ID = get_the_id();

		$comments = get_comments(array('post_id' => $post_ID));
		$countcomments = count($comments);
		$single_rate = array();

		foreach($comments as $comment) {
			$single_rate[] = $comment->comment_karma;
		}

		if($countcomments == 0) {
			$rating_status = 'Not Rated';
		} else {
			$rating_status = array_sum($single_rate)/count($single_rate);
		}


		$place_id = get_field('location_picker');
		if( $costOptions && in_array('showcontact', $costOptions) ) {
			if(in_array('overridecontact', $costOptions)) {
				$contact_status = get_field('contact');
			} elseif(!empty($place_id)) {
				$contact_status = get_field('contact', 'lugares_'.$place_id);
			} else {
				$contact_status = 'No contact';
			}
		}


		$all_post_ids[] = array(
			'id' => $post_ID,
			'title' => get_the_title(),
			'link' => get_the_permalink(),
			'category' => get_skills(),
			'description' => get_the_content(),
			'place' => get_place_name(),
			'prices' => $finalCost,
			'dates' => $date,
			'images' => $img_url[0],
			'rating' => $rating_status,
			'contact' => $contact_status,
			'order_day' => get_field('order_day'),
		);
	endwhile; wp_reset_postdata(); endif;

	return $all_post_ids;

}
