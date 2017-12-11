<?php

	// Get Hours
	$schHrs = schedule_hours();

	// Get Results Hours (convocatoria) || Date notes for exposiciones y talleres
	if(have_rows('range_date_picker')) { while (have_rows('range_date_picker')) {
		the_row();
		$rsltDay = get_sub_field('results_day');
		$rsltUrl = get_sub_field('results_url');
		$dateNotes = get_sub_field('notes');
		if($rsltDay) {
			$rsltDay = date_i18n('l d \d\e F, Y', strtotime($rsltDay));
			if($rsltUrl) $rsltDay = '<a href="'.$rsltUrl.'">'.$rsltDay.'</a>';
		}
	}}

	// Get Presentors
	$presentor = get_field('presentor');

	// Get Audience
	$audience = get_field('audience');

	// Get Cost
	$costOptions = get_field('cost_options');
	if( $costOptions && in_array('free', $costOptions) ) {
		$finalCost = 'Entrada libre';
	} else {
		// $finalCost = '<strong>$'.get_field('cost').'</strong> ';

		if(have_rows('cost_groups')) : while (have_rows('cost_groups')) : the_row();
			$bC_cost = get_sub_field('cost');
			$bC_group = get_sub_field('group');
			if($bC_cost) {
				$buildCost = '$'. $bC_cost;
				if($bC_group) $buildCost .= ' – ';
			}
			if($bC_group) $buildCost .= $bC_group;
			$buildCost .= ' <br>';
			$finalCost .= $buildCost;
			$buildCost = '';
		endwhile; endif;
	}

	// Get Place
	$place_id = get_field('location_picker');
	$placeTerm = get_place_name();
	$placeUrl = get_place_url();


	// Get Contact
	$contact_text = get_field('contact_text'); // <-- Convocatorias
	if( $costOptions && in_array('showcontact', $costOptions) ) {
		if(is_singular('servicios') || in_array('overridecontact', $costOptions)) {
			if(have_rows('contact')) {
				while (have_rows('contact')) {
					the_row();
					$place_email = get_sub_field('email');
					$place_tel = get_sub_field('tel');
				}
			}
		// Automatic Contact by place.
		} elseif(!empty($place_id)) {
			if(have_rows('contact', 'lugares_'.$place_id)) {
				while (have_rows('contact', 'lugares_'.$place_id)) {
					the_row();
					$place_email = get_sub_field('email');
					$place_tel = get_sub_field('tel');
				}
			}
		} else {
			$contactTerm = 'error';
		}
	}
	$place_tel = false;
	$place_email = false;
	if($place_tel || $place_email) {
		$contactTerm = $place_email;
		if($place_tel && $place_email) $contactTerm .= '<br>';
		$contactTerm .= $place_tel;
	} elseif($contact_text) {
		$contactTerm = '<div style="font-size:.9em;">'. $contact_text.'</div>';
	} else {
		$contactTerm = false;
	}

	// CallToAction button: Tickets

	if( $costOptions && in_array('tickets', $costOptions) ) {
		$ctaButton = '<a href="'.get_field('ticket_url').'" class="button">';
		if(get_field('ticket_label')) { $ctaButton .= get_field('ticket_label'); }
		else { $ctaButton .= 'Inscríbete'; }
		$ctaButton .= '</a>';
	}

	$cnv_btn = get_field('official_options');
	if($cnv_btn == 'url' || $cnv_btn == 'file') {
		$file = get_field('official_file');
		if($cnv_btn == 'url') {
			$ctaButton = '<a href="'.get_field('official_url').'" class="button" target="_blank">';
		} elseif($cnv_btn == 'file') {
			$ctaButton = '<a href="'.$file['url'].'" class="button" target="_blank">';
		}
		if(get_field('official_label')) { $ctaButton .= get_field('official_label');
		} else { $ctaButton .= 'Descarga convocatoria'; }
		$ctaButton .= '</a>';
	} ?>

<div class="post_meta">
	<dl><?php


	if(is_singular('cineteca')) {
		echo mta_future_schedule('F d', 'cineteca');
		echo '<style> .atcb-link:focus~ul,.atcb-link:active~ul,.atcb-list:hover{visibility:visible;} </style>';
	} elseif(get_field('dates_options') == 'dates') {
		echo mta_future_schedule('F d Y', 'agenda');
		echo '<style> .atcb-link:focus~ul,.atcb-link:active~ul,.atcb-list:hover{visibility:visible;} </style>';
	} elseif(is_singular('exposiciones') || is_singular('talleres')) { ?>
		<dt class="label">Fechas y Horarios</dt>
		<dd>
			<p><?php echo schedule_days(); ?></p><?php
		if(!empty($schHrs)) { ?>
			<p><?php echo $schHrs; ?></p><?php
		}
		if(!empty($dateNotes)) { ?>
			<p><?php echo $dateNotes; ?></p><?php
		} ?>
		</dd><?php
	} elseif(is_singular('servicios')) {
		if(have_rows('range_date_picker')) { while (have_rows('range_date_picker')) { the_row();
			$notes = get_sub_field('notes');
		}}
		if($notes) {?>
			<dt class="label">Fechas y Horarios</dt>
			<dd><?php echo $notes; ?></dd><?php
		}
	} else { ?>
		<dt class="label">Fechas </dt>
		<dd><?php echo schedule_days(); ?></dd><?php
		if(!empty($schHrs)) { ?>
			<dt class="label">Horarios</dt>
			<dd><?php echo $schHrs; ?></dd><?php
		}
	}

	if(is_singular('convocatorias')) {
		if($rsltDay) { ?>
			<dt class="label">Publicación de Resultados</dt>
			<dd><?php echo $rsltDay; ?></dd><?php
		}
	}

	if($presentor) { ?>
		<dt class="label">Imparte</dt>
		<dd><?php echo $presentor; ?></dd><?php
	}

	if($audience) { ?>
		<dt class="label">Dirigido a</dt>
		<dd><?php echo $audience; ?></dd><?php
	}

	if(is_singular('convocatorias')) {}
	else {
		if($finalCost) { ?>
			<dt class="label">Costo</dt>
			<dd><?php echo $finalCost; ?></dd><?php
		}
	}


	if($placeTerm) { ?>
		<dt class="label">Lugar</dt>
		<dd><a href="<?php echo $placeUrl; ?>" class="location"><?php echo $placeTerm; ?></a></dd><?php
	}


	if($contactTerm) { ?>
		<dt class="label">Contacto</dt>
		<dd><?php echo $contactTerm; ?></dd><?php
	} ?>

	</dl>
	<?php if(!empty($ctaButton)) echo $ctaButton; ?>
</div>
