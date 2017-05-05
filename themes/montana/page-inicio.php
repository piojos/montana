<?php

	get_header();
	if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section id="content" role="main">
	<div class="slider"><?php
	function huge_slide() { ?>
<div class="slide">
	<img src="" alt="">
	<div class="details box">
		<span class="parent_label">Post type + Category (if available)</span>
		<h2>Title</h2>
		<p>Slogan / Subtitle</p>

		<div class="until_label">
			<p>Hasta Marzo 14 (is this dinamic?)</p>
			<p>Location Taxonomy</p>

			<a href="#" class="share button"><img src="http://placehold.it/24" alt=""></a>
			<a href="#" class="share button"><img src="http://placehold.it/24" alt=""></a>
		</div>
	</div>
</div>
<?php }

		echo huge_slide();
		// echo huge_slide();
		// echo huge_slide();
		// echo huge_slide(); ?>
	</div><?php

// Noticias ?>



	<div class="fleft">
		<select id="cd-dropdown" name="cd-dropdown" class="cd-select">
			<option value="-1" selected>Divierten con Historias de Encuentros</option>
			<option value="1" class="icon-monkey">Desarrollan habilidades creativas en el taller de Animación de Animales Fantásticos</option>
			<option value="2" class="icon-bear">Disfrutan Día del Patrimonio de Nuevo León en CONARTE.</option>
			<option value="3" class="icon-squirrel">Aparecen Fantasmas en la Escuela Adolfo Prieto</option>
			<option value="4" class="icon-elephant">Invitan a contemplar los Paisajes de Nuevo León, de David E. Fern, en la Casa de la Cultura</option>
		</select>
	</div>



</section>

<?php endwhile; endif; ?>
<?php get_footer(); ?>
