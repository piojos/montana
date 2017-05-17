<?php

	get_header();
	if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section id="content" role="main">
	<div class="home slider"><?php
	function huge_slide() { ?>
<div class="slide">
	<a href="#">
		<div class="bg_img" style="background-image:url(http://lorempixel.com/1200/800);"></div>
	</a>
	<div class="max_wrap">
		<div class="details box">
			<a href="#">
				<span class="parent_label">Post type + Category (if available)</span>
				<h2>Title</h2>
				<p>Slogan / Subtitle</p>
			</a>
			<div class="status_label">
				<p>Hasta Marzo 14 (is this dinamic?)</p>
				<p>Location Taxonomy</p>

				<a href="#" class="share"><img src="http://placehold.it/24" alt=""></a>
				<a href="#" class="share"><img src="http://placehold.it/24" alt=""></a>
			</div>
		</div>
	</div>
</div>
<?php }

		echo huge_slide();
		echo huge_slide();
		echo huge_slide();
		echo huge_slide(); ?>
	</div><?php




// Noticias ?>

	<div class="dropdown closed max_wrap">
		<div class="latest story">
			<a href="#">
				<img src="http://lorempixel.com/90/60" alt="">
				<div class="title">
					<div>
						<p class="label">Noticias</p>
						<h2>Divierten Con Historias de Encuentros</h2>
					</div>
				</div>
			</a>
		</div>
		<ul class="stories">
			<li>
				<a href="#">
					<img src="http://lorempixel.com/90/60" alt="">
					<div class="title"><div>
						<h2>Desarrollan habilidades creativas en el taller de Animación de Animales Fantásticos</h2>
					</div></div>
				</a>
			</li>
			<li>
				<a href="#">
					<img src="http://lorempixel.com/90/60" alt="">
					<div class="title"><div>
						<h2>Disfrutan Día del Patrimonio de Nuevo León en CONARTE</h2>
					</div></div>
				</a>
			</li>
			<li>
				<a href="#">
					<img src="http://lorempixel.com/90/60" alt="">
					<div class="title"><div>
						<h2>Aparecen Fantasmas en la Escuela Adolfo Prieto</h2>
					</div></div>
				</a>
			</li>
			<li>
				<a href="#">
					<img src="http://lorempixel.com/90/60" alt="">
					<div class="title"><div>
						<h2>Invitan a contemplar los Paisajes de Nuevo León, de David E. Fern, en la Casa de la Cultura</h2>
					</div></div>
				</a>
			</li>
		</ul>
	</div>


	<div class="area max_wrap">
		<h2 class="area_title">¿Qué hacer hoy?</h2>
		<div class="deck "><?php

			echo card('twos');
			echo cards(2, 'fours') ?>
		</div>
	</div>


	<div class="area max_wrap">
		<h2 class="area_title">Hoy en Cineteca</h2>
		<div class="deck"><?php

			echo cards(4, 'fours movie') ?>
		</div>
		<div class="actions_tray">
			<a href="#" class="button">Ver todo hoy</a>
		</div>
	</div>


	<div class="area max_wrap collections">
		<h2 class="area_title">No te pierdas</h2>
		<div class="controls">
			<ul>
				<li><a href="#">Exposiciones Gratuitas</a></li>
				<li><a href="#">Una tarde con niños</a></li>
				<li><a href="#">Conciertos Gratuitos</a></li>
				<li><a href="#">Noches de Poesía</a></li>
				<li><a href="#">Callegenera</a></li>
				<li><a href="#">Esta Semana</a></li>
				<li><a href="#">Festival de Teatro Nuevo León</a></li>
				<li><a href="#">Poder Femenino</a></li>
			</ul>
		</div>
		<div class="details_container">
			<div class="info column">
				<h1>Callegenera</h1>
				<span>Slogan</span>
				<p class="about">wara wara</p>

				<div class="status_label">
					<p><strong>Hasta Marzo 14 (is this dinamic?)</strong></p>
					<p>Location Taxonomy</p>
				</div>
			</div>
			<div class="gallery column">
				<a href="#" class="full"><img src="http://placehold.it/780x400" alt=""></a>
				<a href="#" class="half"><img src="http://placehold.it/380x250" alt=""></a>
				<a href="#" class="half"><img src="http://placehold.it/380x250" alt=""></a>
				<a href="#" class="half"><img src="http://placehold.it/380x250" alt=""></a>
				<a href="#" class="half more"> Ver todo</a>
			</div>
		</div>
	</div>

	<div class="area max_wrap">
		<h2 class="area_title">Esta semana</h2>
		<div class="deck">
			<?php echo cards(8, 'fours'); ?>
		</div>
	</div>

	<div class="area max_wrap">
		<h2 class="area_title">Próximamente</h2>
		<div class="deck">
			<?php echo cards(2, 'twos'); ?>
		</div>
	</div>


</section>
<?php


	endwhile; endif;
	get_footer(); ?>
