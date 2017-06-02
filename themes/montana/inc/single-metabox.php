<?php

	// Get Cost
	$costOptions = get_field('cost_options');
	if( $costOptions && in_array('free', $costOptions) ) {
		$finalCost = '<strong>Entrada gratuita</strong> ';
	} else {
		$finalCost = '<strong>$'.get_field('cost').'</strong> ';
	}
	if(get_field('cost_message')) {
		$finalCost .= get_field('cost_message').'.';
	}

	// Get Tickets
	if( $costOptions && in_array('tickets', $costOptions) ) {
		$ctaButton = '<a href="'.get_field('ticket_url').'" class="button">';
		if(get_field('ticket_label')) { $ctaButton .= get_field('ticket_label'); }
		else { $ctaButton .= 'Inscríbete'; }
		$ctaButton .= '</a>';
	}

	// Get Place
	$placeTerm = get_place();

	// Get Presentors
	$presentor = get_field('presentor');
?>
<div class="post_meta">
	<dl><?php

	if(is_singular('cineteca')) {
		echo movieDays('F d');
		if(!empty(movieHoursClosestday())) {
			echo '<dt class="label">Horarios</dt><dd>'.movieHoursClosestday().'</dd>';
		}
	} else { ?>
		<dt class="label">Fechas</dt>
		<dd><?php echo schedule_days(); ?></dd>
		<dt class="label">Horarios</dt>
		<dd><?php echo schedule_hours(); ?></dd><?php
	}


	if($presentor) { ?>
		<dt class="label">Imparte</dt>
		<dd><?php echo $presentor; ?></dd><?php
	}


	if($finalCost) { ?>
		<dt class="label">Costo</dt>
		<dd><?php echo $finalCost; ?></dd><?php
	}


	if($placeTerm) { ?>
		<dt class="label">Lugar</dt>
		<dd><?php echo $placeTerm; ?></dd><?php
	} ?>

	</dl>
	<?php if(!empty($ctaButton)) echo $ctaButton; ?>
</div>
