<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width" />
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

		<link href="//www.google-analytics.com" rel="dns-prefetch">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">

		<link href="https://fonts.googleapis.com/css?family=Libre+Baskerville:400,400i,700" rel="stylesheet">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<style>
		@font-face {
			font-family: "New June";
			src: url('<?php echo get_template_directory_uri(); ?>/fonts/NewJune-Book.eot');
			src: url('<?php echo get_template_directory_uri(); ?>/fonts/NewJune-Book.eot?#iefix') format('embedded-opentype'),
				url('<?php echo get_template_directory_uri(); ?>/fonts/NewJune-Book.ttf') format('truetype');
			font-weight: normal;
			font-style: normal;
		}
		@font-face {
			font-family: "New June";
			src: url('<?php echo get_template_directory_uri(); ?>/fonts/NewJune-Bold.eot');
			src: url('<?php echo get_template_directory_uri(); ?>/fonts/NewJune-Bold.eot?#iefix') format('embedded-opentype'),
				url('<?php echo get_template_directory_uri(); ?>/fonts/NewJune-Bold.ttf') format('truetype');
			font-weight: 700;
			font-style: normal;
		}

		</style>

		<?php wp_head(); ?>
	</head>


	<body <?php body_class(); ?>>
		<div id="wrapper" class="hfeed">
			<header id="header" role="banner">
				<div id="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_html( get_bloginfo( 'name' ) ); ?>" rel="home">
						<img src="<?php echo get_template_directory_uri(); ?>/img/logo_conarte.svg" alt="">
					</a>
				</div>
				<a href="#" id="nav_toggle_button"></a>
				<div class="navigation">
					<ul class="submenu">
						<li><a href="#">CONARTE</a></li>
						<li><a href="#">Espacios</a></li>
						<li><a href="#">Artistas</a></li>
						<li><a href="#">Patrimonio</a></li>
						<li><a href="#">Contacto</a></li>
					</ul>
					<div id="search">
						<a href="#">Búsqueda</a>
						<?php // get_search_form(); ?>
					</div>
					<hr>
					<nav id="menu" role="navigation">
						<?php // wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
						<ul>
							<li><a href="#">Agenda</a></li>
							<li><a href="#">Cineteca</a></li>
							<li><a href="#">Exposiciones</a></li>
							<li><a href="#">Talleres</a></li>
							<li><a href="#">Convocatorias</a></li>
						</ul>
					</nav>
				</div>
			</header>
			<div id="container">
