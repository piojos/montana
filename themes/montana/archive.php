<?php get_header(); ?>

<section id="content" role="main">

		<div class="area blurry_bg">
			<h2 class="area_title">No te pierdas</h2>
			<div class="deck max_wrap"><?php
				echo cards(2, 'twos'); ?>
			</div>
		</div>


		<div class="area" style="background:white;">
			<h2 class="area_title">Explora CONARTE</h2>
			<div class="deck max_wrap"><?php
				echo cards(6, 'fours'); // sixths?>
			</div>
		</div>


		<div class="area" id="agenda">
			<div class="max_wrap">
				<h2 class="area_title">o Busca Eventos por Fecha y Disciplina</h2>
				<p class="label">Estas viendo eventos de:</p>
				<form class="ag_filter" action="#" method="post">
					<div class="input_wrap hoy">
						<input type="text" name="" value="Lunes Marzo 13, 2017">
					</div>
					<select class="" name="disciplinas">
						<option value="todas">Todas las Disciplinas</option>
						<option value="todas">Exposiciones</option>
						<option value="todas">Talleres</option>
						<option value="todas">Museos</option>
					</select>
					<select class="" name="disciplinas">
						<option value="todas">Todos las Espacios</option>
						<option value="todas">Exposiciones</option>
						<option value="todas">Talleres</option>
						<option value="todas">Museos</option>
					</select>
					<input type="submit" name="" value="Filtrar">
				</form>
			</div>

			<div class="ag_results">

				<div class="external">
					<h3 class="max_wrap">Eventos CONARTE</h3>
					<ul><?php
				function row() { ?>
<li>
	<div class="max_wrap">
		<div class="hour">
			<p>10:00 - 23:00 <br>
			Hasta Marzo 24</p>
		</div>
		<div class="">
			<img src="http://placehold.it/170x70" alt="">
		</div>
		<div class="">
			<h2>Cuentos en Kamishibai</h2>
			<p>En los últimos tiempos han aparecido nuevas
y muy diversas manifestaciones artísticas...</p>
		</div>
		<div class="">
			<p>Centro de las Artes</p>
		</div>
	</div>
</li><?php
				}
					row();
					row();
					row();
					row();
					row(); ?>
					</ul>
				</div>

				<div class="external">
					<h3 class="max_wrap">Eventos Externos</h3>
					<ul><?php
						row();
						row();
						row();
					?></ul>
				</div>
			</div>
		</div>



</section>

<?php get_footer(); ?>
