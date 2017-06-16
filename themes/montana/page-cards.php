<?php

	/* Template Name: Cineteca */
	get_header();

	?>

	<section id="content" role="main"><?php
/*
		$args = array(
			'post_type' => 'cineteca',
			'posts_per_page' => 4
		); ?>
		<div class="area" style="background:white; padding: 3em 0 4em;">
			<h2 class="area_title">Movie Fours</h2>
			<?php deck($args, 'fours movie', 'max_wrap'); ?>
		</div><?php
*/


		$args = array(
			'post_type' => 'talleres',
			'posts_per_page' => 4
		); ?>
		<div class="area" style="padding: 3em 0 4em;">
			<h2 class="area_title">Twos – Talleres</h2>
			<?php slider_deck($args, 'twos', 'max_wrap'); ?>
		</div><?php


		$args = array(
			'post_type' => 'exposiciones',
			'posts_per_page' => 8
		); ?>
		<div class="area" style="background:white; padding: 3em 0 4em;">
			<h2 class="area_title">Fours – Exposiciones</h2>
			<?php slider_deck($args, 'fours', 'max_wrap'); ?>
		</div><?php


		$args = array(
			'post_type' => 'cineteca',
			'posts_per_page' => 8
		); ?>
		<div class="area" style="padding: 3em 0 4em;">
			<h2 class="area_title">Fours – Cineteca</h2>
			<?php slider_deck($args, 'fours movie', 'max_wrap'); ?>
		</div><?php


		$args = array(
			'post_type' => 'agenda',
			// 'offset' => 3,
			'posts_per_page' => 8
		); ?>
		<div class="area" style="background:white; padding: 3em 0 4em;">
			<h2 class="area_title">Sixs – Agenda</h2>
			<?php slider_deck($args, 'sixs', 'max_wrap two_rows');  ?>
		</div><?php


		$args = array(
			'post_type' => 'convocatorias',
			// 'offset' => 3,
			'posts_per_page' => 8
		); ?>
		<div class="area" style="padding: 3em 0 4em;">
			<h2 class="area_title">Fours – convocatorias</h2>
			<?php slider_deck($args, 'fours', 'max_wrap');  ?>
		</div><?php


		$args = array(
			'post_type' => 'colecciones',
			// 'offset' => ,
			'posts_per_page' => 8
		); ?>
		<div class="area" style="background:white; padding: 3em 0 4em;">
			<h2 class="area_title">Fours – Colecciones</h2>
			<?php slider_deck($args, 'fours', 'max_wrap');  ?>
		</div>

	</section>

<?php get_footer(); ?>
