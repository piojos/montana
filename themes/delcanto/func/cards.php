<?php

/*
 * 	CARDS

 * 	Handles every card object that links to a single template and horizontally listed cards.

 * 	1 Functions
 * 	2 Card
 *	3 Large card
 */


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
				$mRating = get_sub_field('rating');
			}
		}
		$string = '<p class="moviemeta">';
		if($mCountry || $mYear || $mDirector) {
			$string .= '<strong>'.$mYear;
			if($mCountry) $string .= ' '.$mCountry;
			if($mDirector) $string .= '. '.$mDirector;
			$string .= '</strong><br>';
		}
		if($mGenre) $string .= $mGenre['label'].' <span>•</span> ';
		if($mLength) $string .= $mLength.' <span>•</span> ';
		$string .= $mRating['label'].'<br>';
		// MISSING: Function to show hours.
		// if($shHour == true) $string .= '<br>14:00 <span>•</span> 17:00 <span>•</span> 21:00';
		$string .= '</p>';
		return $string;
	}



	function get_place_name($id = false) {
		if($id != false) {
			$place_id = $id;
		} else {
			$place_id = get_field('location_picker');
		}
		$place_term = get_term_by('id', $place_id, 'lugares');
		$place_name = $place_term->name;

		$parent_place_id = wp_get_term_taxonomy_parent_id($place_id, 'lugares');

		if($parent_place_id) {
			$parent_term = get_term_by('id', $parent_place_id, 'lugares');
			$parent_slug = $parent_term->slug;
			if($parent_slug != 'otros-espacios') {
				$place_name .= ', '.$parent_term->name;
			}
		}

		return $place_name;
	}

	function get_place_url($id = false) {
		if($id != false) {
			$place_id = $id;
		} else {
			$place_id = get_field('location_picker');
		}
		$place_term = get_term_by('id', $place_id, 'lugares');
		$this_place_url = get_field('placepage_url', 'lugares_'.$place_id);
		$parent_place_id = wp_get_term_taxonomy_parent_id($place_id, 'lugares');

		if($this_place_url) {
			$place_url = $this_place_url;
		} else {
			if($parent_place_id) {
				$parent_term = get_term_by('id', $parent_place_id, 'lugares');
				$place_url = get_field('placepage_url', 'lugares_'.$parent_place_id);
			} else {
				$place_url =  esc_url( home_url('espacios'));
			}
		}

		return $place_url;
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
			if(!is_search()) {
				$placeTerm = get_place_name();
				$string = '<p><strong>'. mta_next_movie('F j') .'</strong></p>';
				if($placeTerm) { $string .= '<p>'.$placeTerm.'</p>'; }
			}
			$string .= '<br>'.movie_meta();
		} elseif(get_post_type() == 'post') {
			$string = '<p style="text-transform: capitalize;"><strong>'.get_the_date('F j ').'</strong> '.get_the_date('Y').'</p>';
		} elseif(get_post_type() == 'servicios') {
			if(have_rows('range_date_picker')) { while (have_rows('range_date_picker')) { the_row();
				$notes = get_sub_field('notes');
			}}
			$string = '<p style="text-transform: capitalize;">'.$notes.'</p>';
		} else {
			$placeTerm = get_place_name();
			$string = '<p>'.schedule_hours().'</p>';
			$string .= '<p><strong>'. schedule_days('F j Y', true, true) .'</strong></p>';
			if($placeTerm) { $string .= '<p>'.$placeTerm.'</p>'; }
		}
		return $string;
	}



	function mta_ftdimg($class = false) {
		// if(!strpos($class, 'no-image')) {
			if(strpos($class, 'week_ftd_post')) {
			} elseif(strpos($class, 'twos')) {
				mta_post_thumbnail($class, 'large');
			} else {
				mta_post_thumbnail($class);
			}
		// }
	}

	function mta_post_thumbnail($class = false, $size = 'medium') {
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
				<div class="schedule"><?php
					if($pt == 'cineteca' || $pt == 'agenda') {
						if(is_singular('colecciones')) {
							if($day != false) {
								echo '<p class="schedule_hours">'. movieHoursClosestday($day, true) .' </p>';
							}
						} elseif($pt == 'cineteca') {
							echo '<p class="schedule_hours">'. movieHoursClosestday($day, true) .' </p>';
						} else {
							if($date_ops == 'dates') {
								echo '<p class="schedule_hours">'. movieHoursClosestday($day, true) .' </p>';
							} else {
								echo '<p class="schedule_hours">'. schedule_hours() .'</p>';
							}
						}
					} elseif( $pt == 'exposiciones' ) {
						if($pt != 'convocatorias') {
							$lc_sched = schedule_hours();
							if(!empty($lc_sched)) echo '<p class="schedule_hours">'. $lc_sched .'</p>';
						}
						if(have_rows('range_date_picker')) {
							while(have_rows('range_date_picker')){
								the_row();
								echo '<p class="schedule_duration">Hasta '. date_i18n( 'F j', strtotime( get_sub_field('end_day') ) ) .'</p>';
							}
						}
					} elseif( $pt == 'talleres' || $pt == 'convocatorias' ) {
						if($pt != 'convocatorias') {
							$lc_sched = schedule_hours();
							if(!empty($lc_sched)) echo '<p class="schedule_hours">'. $lc_sched .'</p>';
						}
						if(have_rows('range_date_picker')) {
							while(have_rows('range_date_picker')){
								the_row();
								if(get_sub_field('start_day') == get_sub_field('end_day')) {
									$fromto = '<span style="text-transform: capitalize;">'.date_i18n( 'F j', strtotime( get_sub_field('start_day') ) ).'</span>';
								} else {
									$fromto =  date_i18n( 'M j', strtotime( get_sub_field('start_day') ) ).' → ';
									$fromto .= date_i18n( 'M j', strtotime( get_sub_field('end_day') ) );
								}
								echo '<p class="schedule_duration">'. $fromto .'</p>';
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
					<p><strong><?php echo get_place_name(); ?></strong></p>
					<?php
						$skills = get_skills();
						$pt = get_post_type();
						if($pt != 'cineteca') {
							if(!empty($skills)) {
								$type = '<p class="type">';
								$type .= $sep.get_skills();
								$type .= '</p>';
							}
						}
						echo $type;
					?>
				</div>
			</a>
		</li><?php
	}





	function deck($args = false, $class = false, $deckClass = false) {

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


	function findIn($haystack = false, $needle = false) {
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


	function checkOddNum($num = false){
		return ($num%2) ? TRUE : FALSE;
	}



	function slider_deck($args = '', $class = '', $deckClass = '', $post_objects = FALSE) {
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




	function getClassofQuery($args = false) {
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




	function keyword_box($class = 'classy', $before = '<span class="parent_label">', $after = '</span>') {
		 // Post type + Category (if available)
		$pt = get_post_type();
		if($pt != 'cineteca') {
			$string = $before;
			$string .= keyword_gen($pt);
			$string .= $after;
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
				$string .= $sep.'Libre';
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
