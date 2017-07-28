<?php

	// Get Hours
	$schHrs = schedule_hours();

	// Get Results Hours (convocatoria)
	if(have_rows('range_date_picker')) { while (have_rows('range_date_picker')) {
		the_row();
		$rsltDay = get_sub_field('results_day');
		$rsltDay = date_i18n('l d \d\e F, Y', strtotime($rsltDay));
		$rsltUrl = get_sub_field('results_url');
		if($rsltUrl) $rsltDay = '<a href="'.$rsltUrl.'">'.$rsltDay.'</a>';
	}}

	// Get Presentors
	$presentor = get_field('presentor');

	// Get Cost
	$costOptions = get_field('cost_options');
	if( $costOptions && in_array('free', $costOptions) ) {
		$finalCost = '<strong>Entrada gratuita</strong> ';
	} elseif($costOptions) {
		$finalCost = '<strong>$'.get_field('cost').'</strong> ';
	}
	if(get_field('cost_message')) {
		$finalCost .= get_field('cost_message').'.';
	}

	// Get Place
	$placeTerm = get_place();

	// Get Contact
	$contact_text = get_field('contact_text');
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
	}
	if($place_tel && $place_email) {
		$contactTerm = $place_email;
		if($place_tel && $place_email)$contactTerm .= ', ';
		$contactTerm .= $place_tel;
	} elseif($contact_text) {
		$contactTerm = '<div style="font-size:.9em;">'. $contact_text.'</div>';
	}

	// CallToAction button: Tickets
	$cnv_btn = get_field('official_options');

	if( $costOptions && in_array('tickets', $costOptions) ) {
		$ctaButton = '<a href="'.get_field('ticket_url').'" class="button">';
		if(get_field('ticket_label')) { $ctaButton .= get_field('ticket_label'); }
		else { $ctaButton .= 'Inscríbete'; }
		$ctaButton .= '</a>';
	} elseif($cnv_btn == 'url' || $cnv_btn == 'file') {
		if($cnv_btn == 'url') {
			$ctaButton = '<a href="'.get_field('official_url').'" class="button" target="_blank">';
		} elseif($cnv_btn == 'file') {
			$ctaButton = '<a href="'.get_field('official_file').'" class="button" target="_blank">';
		}
		if(get_field('official_label')) { $ctaButton .= get_field('official_label');
		} else { $ctaButton .= 'Descarga convocatoria'; }
		$ctaButton .= '</a>';
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


	if($rsltDay) { ?>
		<dt class="label">Publicación de Resultados</dt>
		<dd><?php echo $rsltDay; ?></dd><?php
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
