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
	?><div class="card <?php echo $class; ?>">
		<div class="details box">
			<div class="img_container"><?php
				if(!strpos($class, 'no-image')) {
					echo '<img src="http://placehold.it/500x250" alt="">';
				} ?>
				<span class="parent_label">Post type + Category <?php //(if available) ?></span>
			</div>
			<div class="wrap">
				<h2>Title</h2>

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
