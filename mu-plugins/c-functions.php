<?php
/**
 * Plugin Name: Custom Functions
 * Plugin URI: http://www.raidho.mx
 * Description: General static functions by Raidho.
 * Author: Raidho
 * Author URI: http://www.raidho.mx
 * Version: 0.1.0
 */





/*
 *	Hide Menu OPTS

	function hide_menu() {
		global $current_user;
		$user_id = get_current_user_id();

		if($user_id != '1') {

			remove_menu_page( 'index.php' );                  	//Dashboard
			remove_menu_page( 'upload.php' );                 	//Media
			remove_menu_page( 'edit-comments.php' );          	//Comments
			remove_menu_page( 'plugins.php' );                	//Plugins
				remove_submenu_page( 'themes.php', 'themes.php' );
				remove_submenu_page( 'themes.php', 'theme-editor.php' );
				remove_submenu_page( 'themes.php', 'customize.php' );
			remove_menu_page( 'nav-menus.php' );              	//Editar Menus
			// remove_menu_page( 'users.php' );                  	Users
			remove_menu_page( 'tools.php' );                  	//Tools
			remove_menu_page( 'options-general.php' );        	//Settings
			remove_menu_page( 'edit.php?post_type=acf' );     	//Advanced Custom Fields
			remove_menu_page( 'admin.php?page=cpt_main_menu' );	//Custom Post Types
			remove_menu_page( 'themes.php' );     			//Custom Fields
		}
	}

	add_action('admin_head', 'hide_menu');
 */









/*
 *  Change Posts => Log
 */

	function montana_change_post_label() {
		global $menu;
		global $submenu;
		$menu[5][0] = 'Noticias';
		$submenu['edit.php'][5][0] = 'Noticias';
		$submenu['edit.php'][10][0] = 'Nueva Noticia';
		// $submenu['edit.php'][16][0] = 'Nuevas Etiquetas';
		echo '';
	}
	function montana_change_post_object() {
		global $wp_post_types;
		$labels = &$wp_post_types['post']->labels;
		$labels->name = 'Noticias';
		$labels->singular_name = 'Noticia';
		$labels->add_new = 'Nueva';
		$labels->add_new_item = 'Nueva noticia';
		$labels->edit_item = 'Editar noticia';
		$labels->new_item = 'Nueva';
		$labels->view_item = 'Ver noticia';
		$labels->search_items = 'Buscar noticias';
		$labels->not_found = 'No se encontró';
		$labels->not_found_in_trash = 'No se encontró en la basura';
		$labels->all_items = 'Todas las noticias';
		$labels->menu_name = 'Noticias';
		$labels->name_admin_bar = 'Noticias';
	}

	add_action( 'admin_menu', 'montana_change_post_label' );
	add_action( 'init', 'montana_change_post_object' );
















/*
* Custom Post Types
*/

	function custom_post_types() {
		$args = array(
			'label'               => __( 'agenda'/* , 'montana' */ ),
			'description'         => __( 'Listado de eventos'/* , 'montana' */ ),
			'labels'              => array(
				'name'                => _x( 'Eventos', 'Post Type General Name'/* , 'montana' */ ),
				'singular_name'       => _x( 'Evento', 'Post Type Singular Name'/* , 'montana' */ ),
				'menu_name'           => __( 'Agenda'/* , 'montana' */ ),
				'parent_item_colon'   => __( 'Evento Contenedor' ),
				'all_items'           => __( 'Toda la agenda'/* , 'montana' */ ),
				'view_item'           => __( 'Ver evento'/* , 'montana' */ ),
				'add_new_item'        => __( 'Agregar nuevo evento'/* , 'montana' */ ),
				'add_new'             => __( 'Agregar nuevo'/* , 'montana' */ ),
				'edit_item'           => __( 'Editar evento'/* , 'montana' */ ),
				'update_item'         => __( 'Actualizar evento'/* , 'montana' */ ),
				'search_items'        => __( 'Buscar en Agenda'/* , 'montana' */ ),
				'not_found'           => __( 'No se encontró'/* , 'montana' */ ),
				'not_found_in_trash'  => __( 'No se encontró en la basura'/* , 'montana' */ ),
									),
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
			// 'taxonomies'          => array( 'ubicaciones', 'disciplinas' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 8,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			// 'capability_type'     => 'page',
		);

		register_post_type( 'agenda', $args );




		$args = array(
			'label'               => __( 'cineteca'/* , 'montana' */ ),
			'description'         => __( 'cartelera de cine'/* , 'montana' */ ),
			'labels'              => array(
				'name'                => _x( 'Peliculas', 'Post Type General Name'/* , 'montana' */ ),
				'singular_name'       => _x( 'Pelicula', 'Post Type Singular Name'/* , 'montana' */ ),
				'menu_name'           => __( 'Cineteca'/* , 'montana' */ ),
				// 'parent_item_colon'   => __( 'Pelicula Padre' ),
				'all_items'           => __( 'Todas la peliculas'/* , 'montana' */ ),
				'view_item'           => __( 'Ver pelicula'/* , 'montana' */ ),
				'add_new_item'        => __( 'Agregar nueva pelicula'/* , 'montana' */ ),
				'add_new'             => __( 'Agregar nueva'/* , 'montana' */ ),
				'edit_item'           => __( 'Editar pelicula'/* , 'montana' */ ),
				'update_item'         => __( 'Actualizar pelicula'/* , 'montana' */ ),
				'search_items'        => __( 'Buscar pelicula'/* , 'montana' */ ),
				'not_found'           => __( 'No se encontró'/* , 'montana' */ ),
				'not_found_in_trash'  => __( 'No se encontró en la basura'/* , 'montana' */ ),
									),
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
			// 'taxonomies'          => array( 'ubicaciones', 'disciplinas' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 8,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			// 'capability_type'     => 'page',
		);

		register_post_type( 'cineteca', $args );




		$args = array(
			'label'               => __( 'colecciones'/* , 'montana' */ ),
			'description'         => __( 'Administración de colecciones'/* , 'montana' */ ),
			'labels'              => array(
				'name'                => _x( 'Colecciones', 'Post Type General Name'/* , 'montana' */ ),
				'singular_name'       => _x( 'Colección', 'Post Type Singular Name'/* , 'montana' */ ),
				'menu_name'           => __( 'Colecciones'/* , 'montana' */ ),
				// 'parent_item_colon'   => __( 'Colección Contenedor' ),
				'all_items'           => __( 'Todas las colecciones'/* , 'montana' */ ),
				'view_item'           => __( 'Ver colecciones'/* , 'montana' */ ),
				'add_new_item'        => __( 'Agregar nueva colección'/* , 'montana' */ ),
				'add_new'             => __( 'Agregar nueva'/* , 'montana' */ ),
				'edit_item'           => __( 'Editar colección'/* , 'montana' */ ),
				'update_item'         => __( 'Actualizar colección'/* , 'montana' */ ),
				'search_items'        => __( 'Buscar colección'/* , 'montana' */ ),
				'not_found'           => __( 'No se encontró'/* , 'montana' */ ),
				'not_found_in_trash'  => __( 'No se encontró en la basura'/* , 'montana' */ ),
									),
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
			// 'taxonomies'          => array( 'ubicaciones', 'disciplinas' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 8,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			// 'capability_type'     => 'page',
		);

		register_post_type( 'colecciones', $args );




		$args = array(
			'label'               => __( 'convocatorias'/* , 'montana' */ ),
			'description'         => __( 'Publicación de convocatorias'/* , 'montana' */ ),
			'labels'              => array(
				'name'                => _x( 'Convocatorias', 'Post Type General Name'/* , 'montana' */ ),
				'singular_name'       => _x( 'Convocatoria', 'Post Type Singular Name'/* , 'montana' */ ),
				'menu_name'           => __( 'Convocatorias'/* , 'montana' */ ),
				// 'parent_item_colon'   => __( 'Convocatoria' ),
				'all_items'           => __( 'Todas la convocatorias'/* , 'montana' */ ),
				'view_item'           => __( 'Ver convocatoria'/* , 'montana' */ ),
				'add_new_item'        => __( 'Agregar nueva convocatoria'/* , 'montana' */ ),
				'add_new'             => __( 'Agregar nueva'/* , 'montana' */ ),
				'edit_item'           => __( 'Editar convocatorias'/* , 'montana' */ ),
				'update_item'         => __( 'Actualizar convocatorias'/* , 'montana' */ ),
				'search_items'        => __( 'Buscar convocatorias'/* , 'montana' */ ),
				'not_found'           => __( 'No se encontró'/* , 'montana' */ ),
				'not_found_in_trash'  => __( 'No se encontró en la basura'/* , 'montana' */ ),
									),
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
			// 'taxonomies'          => array( 'ubicaciones', 'disciplinas' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 8,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			// 'capability_type'     => 'page',
		);

		register_post_type( 'convocatorias', $args );




		$args = array(
			'label'               => __( 'talleres'/* , 'montana' */ ),
			'description'         => __( 'Listado de talleres'/* , 'montana' */ ),
			'labels'              => array(
				'name'                => _x( 'Talleres', 'Post Type General Name'/* , 'montana' */ ),
				'singular_name'       => _x( 'Taller', 'Post Type Singular Name'/* , 'montana' */ ),
				'menu_name'           => __( 'Talleres'/* , 'montana' */ ),
				// 'parent_item_colon'   => __( 'Evento Contenedor' ),
				'all_items'           => __( 'Todos la talleres'/* , 'montana' */ ),
				'view_item'           => __( 'Ver taller'/* , 'montana' */ ),
				'add_new_item'        => __( 'Agregar nuevo taller'/* , 'montana' */ ),
				'add_new'             => __( 'Agregar nuevo'/* , 'montana' */ ),
				'edit_item'           => __( 'Editar taller'/* , 'montana' */ ),
				'update_item'         => __( 'Actualizar taller'/* , 'montana' */ ),
				'search_items'        => __( 'Buscar taller'/* , 'montana' */ ),
				'not_found'           => __( 'No se encontró'/* , 'montana' */ ),
				'not_found_in_trash'  => __( 'No se encontró en la basura'/* , 'montana' */ ),
									),
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
			// 'taxonomies'          => array( 'ubicaciones', 'disciplinas' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 8,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			// 'capability_type'     => 'page',
		);

		register_post_type( 'talleres', $args );




		$args = array(
			'label'               => __( 'exposiciones'/* , 'montana' */ ),
			'description'         => __( 'Listado de exposiciones'/* , 'montana' */ ),
			'labels'              => array(
				'name'                => _x( 'Exposiciones', 'Post Type General Name'/* , 'montana' */ ),
				'singular_name'       => _x( 'Exposición', 'Post Type Singular Name'/* , 'montana' */ ),
				'menu_name'           => __( 'Exposiciones'/* , 'montana' */ ),
				// 'parent_item_colon'   => __( 'Exposición Contenedor' ),
				'all_items'           => __( 'Todas las exposciones'/* , 'montana' */ ),
				'view_item'           => __( 'Ver exposición'/* , 'montana' */ ),
				'add_new_item'        => __( 'Agregar nuevo exposición'/* , 'montana' */ ),
				'add_new'             => __( 'Agregar nuevo'/* , 'montana' */ ),
				'edit_item'           => __( 'Editar exposición'/* , 'montana' */ ),
				'update_item'         => __( 'Actualizar exposición'/* , 'montana' */ ),
				'search_items'        => __( 'Buscar exposición'/* , 'montana' */ ),
				'not_found'           => __( 'No se encontró'/* , 'montana' */ ),
				'not_found_in_trash'  => __( 'No se encontró en la basura'/* , 'montana' */ ),
									),
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
			// 'taxonomies'          => array( 'ubicaciones', 'disciplinas' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 8,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			// 'capability_type'     => 'page',
		);

		register_post_type( 'exposiciones', $args );

	}

	add_action( 'init', 'custom_post_types', 0 );












/*
 *  Image handling:
 *
//		1 Tamaños de Imágenes
 */
	add_action( 'after_setup_theme', 'baw_theme_setup' );
	function baw_theme_setup() {
		add_image_size( 'med-sq', 600, 600, true );
		add_image_size( 'larger', 1400, 1400 );
		add_image_size( 'largest', 1800, 1800 );
		add_image_size( 'huge', 2200, 2200 );
	}


//  	2 Tamaños predeterminados

	update_option('thumbnail_size_w', 300);
	update_option('medium_size_w', 600);
	update_option('large_size_w', 1024);


//  	3 Borrar tamaño original de disco y opción

	function replace_uploaded_image($image_data) {
		// if there is no large image : return
		if (!isset($image_data['sizes']['huge'])) return $image_data;

		// paths to the uploaded image and the large image
		$upload_dir = wp_upload_dir();
		$uploaded_image_location = $upload_dir['basedir'] . '/' .$image_data['file'];
		$large_image_filename = $image_data['sizes']['huge']['file'];

		// Do what wordpress does in image_downsize() ... just replace the filenames ;)
		$image_basename = wp_basename($uploaded_image_location);
		$large_image_location = str_replace($image_basename, $large_image_filename, $uploaded_image_location);

		// delete the uploaded image
		unlink($uploaded_image_location);

		// rename the large image
		rename($large_image_location, $uploaded_image_location);

		// update image metadata and return them
		$image_data['width'] = $image_data['sizes']['huge']['width'];
		$image_data['height'] = $image_data['sizes']['huge']['height'];
		unset($image_data['sizes']['huge']);

		// Check if other size-configurations link to the large-file
		foreach($image_data['sizes'] as $size => $sizeData) {
			if ($sizeData['file'] === $large_image_filename)
			unset($image_data['sizes'][$size]);
		}

		return $image_data;
	}
	add_filter('wp_generate_attachment_metadata', 'replace_uploaded_image');


	function theme_t_wp_set_image_size_options( $sizes ){
		$sizes = array(
			'thumbnail' => 'Miniatura',
			'medium' => 'Mediana',
			'large' => 'Grande',
			'larger' => __( 'Mas grande' ),
			'largest' => __( 'Grandísimo' ),
			'huge' => __( 'Gigantesco' )
		);
		return $sizes;
	}
	add_filter('image_size_names_choose', 'theme_t_wp_set_image_size_options');







/*
 *  Allow [.svg]
 */

	function cc_mime_types( $mimes ){
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
	add_filter( 'upload_mimes', 'cc_mime_types' );








/*
 *  WYSIWYG Mods
 */

	/*	COLORS  */
	// function my_mce4_new_colors( $init ) {
	// 	$default_colours = '';
	// 	$custom_colours = ' "d93636", "Red", "00aacd", "Blue", "FFFFFF", "White" ';
	// 	$init['textcolor_map'] = '['.$custom_colours.','.$default_colours.']';
	// 	return $init;
	// }
	//
	// add_filter('tiny_mce_before_init', 'my_mce4_new_colors');


	/*	ACF  */
	// add_filter( 'acf/fields/wysiwyg/toolbars' , 'my_toolbars'  );
	// function my_toolbars( $toolbars ) {
	//
	// 	$toolbars['Proyect Basic'][1] = array('forecolor' , 'bold' , 'italic' , 'link' , 'unlink' , 'removeformat' );
	//
	// 	// $toolbars['Normal' ] = array();
	// 	// $toolbars['Normal' ][1] = array('styleselect' , 'bold' , 'forecolor' , 'alignleft aligncenter' , 'bullist' , 'indent' , 'outdent' , 'link' , 'unlink' , 'removeformat');
	//
	// 	// remove the 'Full' toolbar completely
	// 	// unset( $toolbars['Full' ] );
	// 	// unset( $toolbars['Basic' ] );
	//
	// 	// return $toolbars - IMPORTANT!
	// 	return $toolbars;
	// }








	/**
	 * Grab latest post title by an author!
	 *
	 * @param array $data Options for the function.
	 * @return string|null Post title for the latest,  * or null if none.
	 */
	// function my_awesome_func( $data ) {
	// 	$posts = get_posts( array(
	// 		'author' => $data['id'],
	// 	) );
	//
	// 	if ( empty( $posts ) ) {
	// 		return null;
	// 	}
	//
	// 	return $posts[0]->post_title;
	// }
	//
	//
	// add_action( 'rest_api_init', function () {
	// 	register_rest_route( 'fun/v1', '/cine/(?P<id>\d+)', array(
	// 		'methods' => 'GET',
	// 		'callback' => 'cine',
	// 	) );
	// } );
	//
	// function my_awesome_func( WP_REST_Request $request ) {
	// 	// You can access parameters via direct array access on the object:
	// 	$param = $request['some_param'];
	//
	// 	// Or via the helper method:
	// 	$param = $request->get_param( 'some_param' );
	//
	// 	// You can get the combined, merged set of parameters:
	// 	$parameters = $request->get_params();
	//
	// 	// The individual sets of parameters are also available, if needed:
	// 	$parameters = $request->get_url_params();
	// 	$parameters = $request->get_query_params();
	// 	$parameters = $request->get_body_params();
	// 	$parameters = $request->get_json_params();
	// 	$parameters = $request->get_default_params();
	//
	// 	// Uploads aren't merged in, but can be accessed separately:
	// 	$parameters = $request->get_file_params();
	// }










/*  The End  */
