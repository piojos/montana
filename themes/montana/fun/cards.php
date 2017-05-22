<?php

	function card($class) {
		// eights (smallest, home)
		// fours (common)
		// twos
		// threes

		// featured (home)
		// movie (cineteca)
		// poster (cineteca)
		// video? (home, más sencilla)

		if(has_post_thumbnail()) { $class .= ' has-image'; }
		else { $class .= ' no-image'; }


	?><div class="card <?php echo $class; ?>">
		<div class="details box">
			<div class="img_container"><?php
				if(!strpos($class, 'no-image')) the_post_thumbnail(); ?>
				<span class="parent_label"><?php echo get_post_type(); // Post type + Category (if available) ?></span>
			</div>
			<div class="wrap">
				<h2><?php the_title(); ?></h2>

				<div class="status_label">
					<p><strong>Hasta Marzo 14 <?php //(is this dinamic?) ?></strong></p>
					<p>Location Taxonomy</p>
				</div>
			</div>
		</div>
	</div><?php
	}


	function cards($loops, $class) {
		for ($x = 1; $x <= $loops; $x++) {
			$bunch .= card($class);
		}
		return $bunch;
	}

	function agenda_card() { ?>
		<li>
			<a class="max_wrap" href="<?php the_permalink(); ?>">
				<div class="schedule">
					<p><strong><?php echo schedule_hours(); ?></strong> <br>
					<?php echo schedule_days(); ?>
					</p>
				</div>
				<div class="thumbnail">
					<?php the_post_thumbnail(); ?>
				</div>
				<div class="title">
					<h2><strong><?php the_title(); ?></strong></h2>
					<p><?php the_excerpt(); ?></p>
				</div>
				<div class="location">
					<p><strong>Centro de las Artes</strong></p>
				</div>
			</a>
		</li><?php
	}


	function deck($args, $class) {

		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) { ?>
		<div class="deck"><?php
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				echo card($class);
			} ?>
		</div><?php
			wp_reset_postdata();
		}
	}
