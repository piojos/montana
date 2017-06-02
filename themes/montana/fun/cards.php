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



	function card($class) {
		// eights (smallest, home)
		// fours (common)
		// twos
		// threes

		// featured (home)
		// movie (cineteca)
		// poster (cineteca)

	// video? (home, más sencilla)
		// if(strpos($class, 'movie')) {}


	// Manage having ftd image or movie poster.
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
					} elseif(!strpos($class, 'twos')) {
						the_post_thumbnail('large');
					} else {
						the_post_thumbnail();
					}
				} ?>
				<span class="parent_label"><?php echo get_post_type(); // Post type + Category (if available) ?></span>
			</div>
			<div class="wrap">
				<h2><?php the_title(); ?></h2>

				<div class="status_label"><?php
				if(strpos($class, 'movie')) {
					echo movie_meta();
				} else { ?>
					<p><strong>Hasta Marzo 14</strong></p>
					<p>Location Taxonomy</p><?php
					// if(strpos($class, 'fours')) echo '<p>'.schedule_days().'</p>';
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




	function list_card() { ?>
		<li>
			<a class="max_wrap" href="<?php the_permalink(); ?>">
				<div class="schedule">
					<p><?php echo schedule_hours(); ?> <br><?php

					if( get_post_type( $post->ID ) == 'exposiciones') {
						if(have_rows('range_date_picker')) { while(have_rows('range_date_picker')){
							the_row();
							echo 'Hasta '.date_i18n( 'F d', strtotime( get_sub_field('end_day') ) );
						}}
					}

					// echo schedule_days(); ?>
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
