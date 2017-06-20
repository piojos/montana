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



	function card($class, $setData = false) {

	// Manage having ftd image or movie poster.
		if($setData) setup_postdata($setData);
		if(has_post_thumbnail() OR get_field('poster_img')) { $class .= ' has-image'; }
		else { $class .= ' no-image'; }


	?><div class="card <?php echo $class; ?>">
		<div class="details box">
			<a href="<?php the_permalink(); ?>">
			<div class="img_container"><?php
				if(!strpos($class, 'no-image')) {
					if(strpos($class, 'movie')) {
						$image = get_field('poster_img');
						if( !empty($image) ){ ?>
				<img src="<?php echo $image['sizes']['poster']; ?>" alt="<?php echo $image['alt']; ?>" /><?php
						}
					} elseif(strpos($class, 'twos')) {
						the_post_thumbnail('medium');
					} else {
						the_post_thumbnail('medium');
					}
				}
				echo keyword_box($class); ?>
			</div>
			<div class="wrap">
				<h2><?php the_title(); ?></h2>

				<div class="status_label"><?php
				if(strpos($class, 'movie')) {
					echo movie_meta();
				} else {
					$placeTerm = get_place(); ?>
					<p><strong><?php echo schedule_days('F j y', true, true); ?></strong></p>
					<?php if($placeTerm) echo '<p>'.$placeTerm.'</p>';
				} ?>
				</div>
			</div>
			</a>
		</div>
	</div><?php
	}




	function cards($loops, $class) {
		for ($x = 1; $x <= $loops; $x++) {
			$bunch .= card($class);
		}
		return $bunch;
	}




	function list_card() {
		$pt = get_post_type( $post->ID ); ?>
		<li>
			<a class="max_wrap" href="<?php the_permalink(); ?>">
				<div class="schedule">
					<p><?php if($pt != 'convocatorias') echo schedule_hours().' <br>'; ?><?php
					if( $pt == 'exposiciones' OR $pt == 'convocatorias') {
						if(have_rows('range_date_picker')) { while(have_rows('range_date_picker')){
							the_row();
							echo 'Hasta '.date_i18n( 'F d', strtotime( get_sub_field('end_day') ) );
						}}
					}
					if(!empty(movieHoursClosestday())) {
						echo movieHoursClosestday(true);
					} ?>
					</p>
				</div>
				<div class="thumbnail">
					<?php the_post_thumbnail(); ?>
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





	function slider_deck($args, $class = '', $deckClass = '', $post_objects = FALSE) {
		if($post_objects == true) {
			global $post;
			if( $args ) { ?>

			<div class="deck slider_deck<?php echo ' '.$deckClass; ?>"><?php
				foreach( $args as $post):
					setup_postdata($post);
					echo card($class, $post);
				endforeach; ?>
			</div><?php
				wp_reset_postdata();
			}

		} else {
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) { ?>

			<div class="deck slider_deck<?php echo ' '.$deckClass; ?>"><?php
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					echo card($class);
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
			if(in_array('free', $costOpts)) {
				$string .= $sep.'Gratuita';
			} else {
				$string .= get_skills();
			}
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
