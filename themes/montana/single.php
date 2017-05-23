<?php

	get_header();
	if(have_posts()) : while (have_posts()) : the_post(); ?>

<section id="content" role="main" class="single">
	<div class="header">

		<div class="bg_featured_banner" style="background-image:url(<?php the_post_thumbnail_url('huge'); ?>)"></div>

		<div class="max_wrap post_head">
			<div class="post_info main_content_column">
				<p class="label"><?php echo get_post_type(); ?></p>
				<h1><?php the_title(); ?></h1>
				<?php if(get_field('kicker')) echo '<p class="subtitle">'.get_field('kicker').'</p>'; ?>

				<div class="excerpt"><?php
					if(is_singular('cineteca')) {
						$image = get_field('poster_img');
						if( !empty($image) ){ ?>
							<img src="<?php echo $image['sizes']['poster']; ?>" class="poster" alt="<?php echo $image['alt']; ?>" /><?php
						}
						echo movie_meta(false);
					}
					the_content(); ?>
				</div>

				<div class="status_label">
					<a href="#" class="share"><img src="http://placehold.it/24" alt=""></a>
					<a href="#" class="share"><img src="http://placehold.it/24" alt=""></a>
				</div>
			</div><?php
			if(is_singular(array('agenda', 'cineteca'))) {
				get_template_part('inc/single', 'metabox');
			} ?>
		</div>
	</div>

	<div class="post_body max_wrap"><?php

		if(is_singular('colecciones')) {
			$post_objects = get_field('collection');
			// print_r($col); ?>
			<ul class="ag_results">
				<li>
					<div class="max_wrap">
						<h3><span>PREFIX</span>Lunes Marzo 13 2017</h3>
					</div><?php

					if( $post_objects ) { ?>
					<ul><?php
						foreach( $post_objects as $post) {
							setup_postdata($post);
							agenda_card();
						} ?>
					</ul><?php
					wp_reset_postdata();
					} ?>
				</li>
			</ul><?php

		} else { ?>

		<div class="main_content_column"><?php
			get_template_part('inc/widgets'); ?>
		</div><?php
		} ?>


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
				<?php echo cards(4, 'movie fours'); ?>
			</div>
		</div>
	</div>
</section>

<?php

	endwhile; endif;
	get_footer(); ?>
