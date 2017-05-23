<?php

	$costOptions = get_field('cost_options');
	if( $costOptions && in_array('free', $costOptions) ) {
		$finalCost = '<strong>Entrada gratuita</strong>';
	} else {
		$finalCost = '<strong>'.get_field('cost').'</strong>';
	}
	$finalCost .= get_field('cost_message');

	if( $costOptions && in_array('tickets', $costOptions) ) {
		$ctaButton = '<a href="'.get_field('ticket_url').'" class="button">';
		if(get_field('ticket_label')) { $ctaButton .= get_field('ticket_label'); }
		else { $ctaButton .= 'Inscríbete'; }
		$ctaButton .= '</a>';
	}

	 ?>
<div class="post_meta">
	<dl>
		<dt class="label">Fecha</dt>
		<dd><?php echo schedule_days(); ?></dd>

		<dt class="label">Hora</dt>
		<dd><?php echo schedule_hours(); ?></dd>

		<dt class="label">Costo</dt>
		<dd><?php echo $finalCost; ?></dd>

		<dt class="label">Ubicación</dt>
		<dd>Explanada de Museo de Historia Mexicana</dd>
	</dl>
	<?php if(!empty($ctaButton)) echo $ctaButton; ?>
</div>
