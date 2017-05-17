<?php get_header(); ?>

<section id="content" role="main" class="single">
	<div class="header">

		<div class="bg_featured_banner" style="background-image:url(http://placehold.it/2000x1000)"></div>

		<div class="max_wrap post_head">
			<div class="post_info main_content_column">
				<p class="label">Noticias</p>
				<h1><?php the_title(); ?></h1>
				<p class="subtitle">Slogan / Subtitle</p>
				<div class="about excerpt">
					<p>Lorem Ipsum dolor <strong>sit amet</strong>. Etc etc etc.</p>
				</div>

				<div class="status_label">
					<p>Hasta Marzo 14 (is this dinamic?)</p>
					<p>Location Taxonomy</p>

					<a href="#" class="share"><img src="http://placehold.it/24" alt=""></a>
					<a href="#" class="share"><img src="http://placehold.it/24" alt=""></a>
				</div>
			</div>
			<div class="post_meta">
				<ul>
					<li>
					<p class="label">Fecha</p>
					<p>De Marzo a tal</p></li>
					<li>
					<p class="label">Hora</p>
					<p>10:30 H</p></li>
					<li>
					<p class="label">Costo</p>
					<p>$1500 Incluye materiales</p></li>
					<li>
					<p class="label">Ubicación</p>
					<p>Explanada de Museo de Historia Mexicana</p></li>
				</ul>
				<a href="#" class="button"> Inscríbete </a>
			</div>
		</div>
	</div>

	<div class="post_body max_wrap">
		<div class="main_content_column">
			<div class="widget simple">
				<h3>Mollit anim id est laborum.</h3>
				<hr>
				<div class="body_content">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt.</p>
				</div>
			</div>

			<div class="widget program">
				<h3>Programa</h3>
				<hr>
				<div class="body_content">
					<ol>
						<li>Cosa <span>Mar 10 / 00:00</span></li>
						<li>Otra Cosa <span>00:00</span></li>
						<li>Mas Cosas <span>Mar 10</span></li>
						<li>Muchas Cosa <span>Mar 10</span></li>
						<li>Cosinas <span>Mar 10</span></li>
						<li>Cosa <span>Mar 10</span></li>
					</ol>
				</div>
			</div>
			<div class="widget program">
				<h3>Acerca del Instructor</h3>
				<hr>
				<div class="body_content">
					<img src="http://placehold.it/400x250" alt="">
					<p><strong>Juanita Banana</strong></p>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
				</div>
			</div>
			<div class="widget program">
				<h3>trailer</h3>
				<hr>
				<div class="body_content">
					<iframe width="560" height="315" src="https://www.youtube.com/embed/v7MGUNV8MxU" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
		</div><?php // Main Content ?>


		<div class="area max_wrap">
			<h2 class="area_title">Otras fechas y horarios para Trainspotting</h2>
			<div class="deck">
				<?php echo cards(4, 'schedules fours'); ?>
			</div>
			<div class="actions_tray">
				<a href="#" class="button">Ver todo</a>
			</div>
		</div>


		<div class="area max_wrap">
			<h2 class="area_title">Mas en Cineteca</h2>
			<div class="deck">
				<?php echo cards(8, 'movie fours'); ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
