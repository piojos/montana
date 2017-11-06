<?php get_header(); ?>

<section id="content" role="main">
	<div class="area max_wrap">
		<h1>404 – Esta página no existe.</h1>
		<p>Regresa al <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_html( get_bloginfo( 'name' ) ); ?>" class="link">sitio principal</a>.</p>
	</div>
</section>

<?php get_footer(); ?>
