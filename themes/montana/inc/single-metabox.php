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

	// Get Hours
	$schHrs = schedule_hours();

	// Get Contact
	if( $costOptions && in_array('showcontact', $costOptions) ) {
		$place_id = get_field('location_picker');
		if(in_array('overridecontact', $costOptions)) {
			if(have_rows('contact')) {
				while (have_rows('contact')) {
					the_row();
					$place_email = get_sub_field('email');
					$place_tel = get_sub_field('tel');
				}
			}
		} elseif(!empty($place_id)) {
			if(have_rows('contact', 'lugares_'.$place_id)) {
				while (have_rows('contact', 'lugares_'.$place_id)) {
					the_row();
					$place_email = get_sub_field('email');
					$place_tel = get_sub_field('tel');
				}
			}
		} else {
			// $contactTerm = 'error';
		}

	$contactTerm = $place_email;
	if($place_tel && $place_email)$contactTerm .= ', ';
	$contactTerm .= $place_tel;

	} ?>

<div class="post_meta">
	<dl><?php



	if(is_singular('cineteca')) {
		echo mta_future_schedule('F d', 'cineteca');
	} elseif(get_field('dates_options') == 'dates') {
		echo mta_future_schedule('F d Y', 'agenda');

	} else { ?>
		<dt class="label">Fechas</dt>
		<dd><?php echo schedule_days(); ?></dd><?php
		if(!empty($schHrs)) { ?>
			<dt class="label">Horarios</dt>
			<dd><?php echo $schHrs; ?></dd><?php
		}
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
	}


	if($contactTerm) { ?>
		<dt class="label">Contacto</dt>
		<dd><?php echo $contactTerm; ?></dd><?php
	} ?>

	</dl>
	<?php if(!empty($ctaButton)) echo $ctaButton; ?>
</div>
