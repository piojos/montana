<?php


	function movie_meta($shHour = false) {
		// NOTA: $post use postid?
		if(have_rows('meta')){
			while (have_rows('meta')) {
				the_row();
				$mYear = get_sub_field('year');
				$mCountry = get_sub_field('countries');
				$mDirector = get_sub_field('director');
				$mGenre = get_sub_field('genre');
				$mLength = get_sub_field('length');
				$mRating = get_sub_field('rating');
			}
		}
		$string = '<p class="moviemeta">';
		$string .= '<strong>'.$mYear.' '.$mDirector.'</strong><br>';
		if($mGenre) $string .= $mGenre['label'].' <span>•</span> ';
		if($mLength) $string .= $mLength.' <span>•</span> ';
		$string .= $mRating['label'].'<br>';
		// MISSING: Function to show hours.
		// if($shHour == true) $string .= '<br>14:00 <span>•</span> 17:00 <span>•</span> 21:00';
		$string .= '</p>';
		return $string;
	}




	function get_place() {
		$aTerm = get_field('location_picker');
		$placeTerm = get_term_by('id', $aTerm, 'lugares');
		return $placeTerm->name;
	}



	function card($class = false, $setData = false, $day = false) {

	// Manage having ftd image or movie poster.
		if($setData) setup_postdata($setData);
		// if(has_post_thumbnail() OR get_field('poster_img')) {
		// 	if(strpos($class, 'week_ftd_post')) {
		// 		$class .= ' no-image';
		// 	} else {
		// 		$class .= ' has-image';
		// 	}
		// } else { $class .= ' no-image'; }


	?><div class="card <?php echo $class; ?>"><?php

		if(strpos($class, 'week_ftd_post')) { ?>
			<div class="img_background">
				<?php the_post_thumbnail('large'); ?>
			</div><?php
		} ?>
		<div class="details box">
			<a href="<?php the_permalink(); ?>">
			<div class="img_container"><?php

				mta_ftdimg($class);
				echo keyword_box($class); ?>
			</div>
			<div class="wrap">
				<h2><?php the_title(); ?></h2>

				<div class="status_label">
					<?php echo mnt_card_status_label(); ?>
				</div>
			</div>
			</a>
		</div>
	</div><?php
	}




	function mnt_card_status_label() {
		if(get_post_type() == 'colecciones') {
			$dates = get_field('coll_dates');
			$locations = get_field('coll_locations');
			if($dates) { $string = '<p><strong>'.$dates.'</strong></p>'; }
			if($locations) { $string .= '<p>'.$locations.'</p>'; }
		} elseif(get_post_type() == 'cineteca') {
			$placeTerm = get_place();
			$string = '<p><strong>'. mta_next_movie('F j') .'</strong></p>';
			if($placeTerm) { $string .= '<p>'.$placeTerm.'</p>'; }
			$string .= '<br>'.movie_meta();
		} else {
			$placeTerm = get_place();
			$string = '<p>'.schedule_hours().'</p>';
			$string .= '<p><strong>'. schedule_days('F j Y', true, true) .'</strong></p>';
			if($placeTerm) { $string .= '<p>'.$placeTerm.'</p>'; }
		}
		return $string;
	}



	function mta_ftdimg($class) {
		// if(!strpos($class, 'no-image')) {
			if(strpos($class, 'week_ftd_post')) {
			} elseif(strpos($class, 'twos')) {
				mta_post_thumbnail($class, 'large');
			} else {
				mta_post_thumbnail($class);
			}
		// }
	}

	function mta_post_thumbnail($class, $size = 'medium') {
		$place_id = get_field('location_picker');
		$taxonomy = get_term_by('id', $place_id, 'lugares');
		$myterms = get_terms( array( 'lugares' => $taxonomy->slug, 'parent' => 0 ) );
		$coll_obj = get_field('collection_picker');


		if(strpos($class, 'movie')) {
			$image = get_field('poster_img');
			if( !empty($image) ){ ?>
	<img src="<?php echo $image['sizes']['poster']; ?>" alt="<?php echo $image['alt']; ?>" /><?php
			}
		} elseif(has_post_thumbnail()) {
			the_post_thumbnail($size);

		} elseif($coll_obj) {			// Get Collection's image
			$coll_thumb = get_the_post_thumbnail($coll_obj, $size);
			echo $coll_thumb;

		} elseif($place_id) {			// Get lugar's image

			$image = get_field('img', 'lugares_'.$place_id);
			if( !empty($image) ){ ?>
	<img src="<?php echo $image['sizes'][$size]; ?>" alt="<?php echo $image['alt']; ?>" /><?php
			} elseif($taxonomy->parent >= 1) {
				$image = get_field('img', 'lugares_'.$taxonomy->parent);
				if( !empty($image) ){ ?>
	<img src="<?php echo $image['sizes'][$size]; ?>" alt="<?php echo $image['alt']; ?>" /><?php
				}
			}
		} else {
			$image = get_field('main_fallback_img', 'option');
			if( !empty($image) ){ ?>
	<img src="<?php echo $image['sizes'][$size]; ?>" alt="<?php echo $image['alt']; ?>" /><?php
			}
		}
	}




	function list_card($day = false) {
		$pt = get_post_type( $post->ID );
		$date_ops = get_field('dates_options'); ?>
		<li>
			<a class="max_wrap" href="<?php the_permalink(); ?>">
				<div class="schedule">
					<p><?php
					if($pt == 'cineteca' || $pt == 'agenda') {
						if(is_singular('colecciones')) {
							if($day != false) {
								echo movieHoursClosestday($day, true);
							}
						} elseif($pt == 'cineteca') {
							echo movieHoursClosestday(false, true);
						} else {
							if($date_ops == 'dates') {
								echo movieHoursClosestday(false, true);
							} else {
								echo schedule_hours();
							}
						}
					} elseif( $pt == 'exposiciones' || $pt == 'convocatorias' || $pt == 'talleres') {
						if($pt != 'convocatorias') {
							$lc_sched = schedule_hours();
							if(!empty($lc_sched)) echo $lc_sched.' <br>';
						}
						if(have_rows('range_date_picker')) {
							while(have_rows('range_date_picker')){
								the_row();
								echo 'Hasta '.date_i18n( 'F j', strtotime( get_sub_field('end_day') ) ).'.';
							}
						}
					} else {}
					?>
					</p>
				</div>
				<div class="thumbnail">
					<?php mta_post_thumbnail(); ?>
				</div>
				<div class="title">
					<h2><strong><?php the_title(); ?></strong></h2>
					<div class="kicker"><?php
						if(get_field('kicker')) { echo '<p>'.get_field('kicker').'</p>'; }
						else { the_excerpt(); } ?>
					</div>
				</div>
				<div class="location">
					<p><strong><?php echo get_place(); ?></strong></p>
				</div>
			</a>
		</li><?php
	}





	function deck($args, $class = '', $deckClass = '') {

		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) { ?>
		<div class="deck<?php echo ' '.$deckClass; ?>"><?php
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				echo card($class);
			} ?>
		</div><?php
			wp_reset_postdata();
		}
	}


	function findIn($haystack = FALSE, $needle = FALSE) {
		// EXISTS. is exactly OR in array. MISSING: OR in string
		if($haystack && $needle) {
			if($haystack == $needle) {
				$result = TRUE;
				$debug = 'Found exactly.';
			}
			if(is_array($haystack) && in_array($needle, $haystack)) {
				$result = TRUE;
				$debug = 'Found in array.';
			} elseif(strpos($haystack, $needle)) {
				$result = TRUE;
				$debug = 'Found in string.';
			} else {
				$result = FALSE;
			}
		} else {
			$debug = 'ERROR: Missing variables';
		}
		// return $debug;
		return $result;
	}


	function checkOddNum($num){
		return ($num%2) ? TRUE : FALSE;
	}



	function slider_deck($args, $class = '', $deckClass = '', $post_objects = FALSE) {
		$checkWeek = strpos($deckClass, 'this_week');
		$checkFtd = strpos($deckClass, 'has_ftd_post');
		if($checkWeek !== FALSE) { $isThisWeek = TRUE; } // Valid for Home: .area.this_week
		$i = 0;
		if($post_objects == true) {
			global $post;
			if( $args ) {
				$amount = count($args);
				if($amount <= 4 && ($amount >= 7 && $amount <= 8) ) {
					$class .= 'fours';
				}
				// if $class = 'fours';  ?>
			<div class="deck slider_deck<?php echo ' '.$deckClass; ?>"><?php
				foreach( $args as $post):
					setup_postdata($post);
					if($isThisWeek && $amount > 6 ) {
						++$i;
						if(checkOddNum($i)) echo '<div class="row '.$class.'">';
						echo card($class, $post);
						if(!checkOddNum($i)) echo '</div>';
					} else {
						echo card($class, $post);
					}
				endforeach; ?>
			</div><?php
				wp_reset_postdata();
			}

		} else {
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				$amount = count($the_query->posts);
				if($amount <= 4 && ($amount >= 7 && $amount <= 8) ) {
					$class .= 'fours';
				} ?>
			<div class="deck slider_deck<?php echo ' '.$deckClass; ?>"><?php

				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					if( ($isThisWeek && $amount > 6) || ($checkFtd && $amount > 4) ) {
						++$i;
						if(checkOddNum($i)) echo '<div class="row '.$class.'">';
						echo card($class, $post);
						if(!checkOddNum($i)) echo '</div>';
					} else {
						echo card($class, $post);
					}
				} ?>
			</div><?php
				wp_reset_postdata();
			}
		}
	}




	function getClassofQuery($args) {
		// if WP LOOP
		// $custom_posts = new WP_Query($args);
		// $count = $custom_posts->post_count;
		// else
		$count = count($args);
		if($count <= 2) {
			$class = ' twos';
		} else {
			$class = ' fours';
		}
		return $class;
	}




	function keyword_box($class = 'classy') {
		 // Post type + Category (if available)
		$pt = get_post_type();
		if($pt != 'cineteca') {
			$string = '<span class="parent_label">';
			$string .= keyword_gen($pt);
			$string .= '</span>';
		}
		return $string;
	}


	function keyword_gen($pt = false, $ext = false) {
		if($ext == true) {
			$string = $pt;
			$sep = ' • ';
		}
		if($pt == 'exposiciones') {
			$costOpts = get_field('cost_options');
			if($costOpts && in_array('free', $costOpts)) {
				$string .= $sep.'Gratuita';
			} else {
				$string .= get_skills();
			}
		} elseif($pt == 'post') {
			$string = 'Noticias';
		} elseif($pt == 'page') {
			$string = 'Información';
		} else {
			$skills = get_skills();
			if(!empty($skills)) {
				$string .= $sep.get_skills();
			} elseif($ext == false) {
				$string .= ucfirst(strtolower($pt));
			}
		}
		// $string = 'all ok!';
		return $string;
	}
