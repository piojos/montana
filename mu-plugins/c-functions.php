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
 */
	// function hide_menu() {
	// 	global $current_user;
	// 	$user_id = get_current_user_id();
	// 	if($user_id != '1') {
	// 		remove_menu_page( 'edit-comments.php' );          	//Comments
	// 	}
	// }
	// add_action('admin_head', 'hide_menu');

	// Remove Customize from admin bar
	function montana_edit_admin_bar() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_node('comments');
		$wp_admin_bar->remove_menu('customize');
	}
	add_action( 'wp_before_admin_bar_render', 'montana_edit_admin_bar' );

	function montana_unregister_tags() {
		unregister_taxonomy_for_object_type('post_tag', 'post');
		unregister_taxonomy_for_object_type('category', 'post');
	}
	add_action('init', 'montana_unregister_tags');

	add_action('pre_get_posts', 'include_tags_for_movies');
	function include_tags_for_movies($query) {
		if($query->is_main_query() && ( is_category() || is_tag() )) {
			$query->set( 'post_type', array('cineteca') );
		}
	}






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








	//hook into the init action and call create_topics_nonhierarchical_taxonomy when it fires

	add_action( 'init', 'create_topics_nonhierarchical_taxonomy', 0 );

	function create_topics_nonhierarchical_taxonomy() {
		// Labels part for the GUI
		$labels = array(
			'name' => _x( 'Lugares', 'taxonomy general name' ),
			'singular_name' => _x( 'Lugar', 'taxonomy singular name' ),
			'search_items' =>  __( 'Busca Lugares' ),
			'popular_items' => __( 'Lugares Populares' ),
			'all_items' => __( 'Todos los Lugares' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Editar lugar' ),
			'update_item' => __( 'Actualizar lugar' ),
			'add_new_item' => __( 'Agregar nuevo lugar' ),
			'new_item_name' => __( 'Nuevo nombre de lugar' ),
			'separate_items_with_commas' => __( 'Separar lugares con commas' ),
			'add_or_remove_items' => __( 'Agregar o quitar lugares' ),
			'choose_from_most_used' => __( 'Escoge de los lugares más usados' ),
			'menu_name' => __( 'Lugares' ),
		);

		register_taxonomy('lugares',array('agenda', 'convocatorias', 'talleres', 'exposiciones'),array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_in_quick_edit' => false,
			'meta_box_cb' => false,
			'show_admin_column' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array( 'slug' => 'lugar' ),
		));


		$labels = array(
			'name' => _x( 'Disciplinas', 'taxonomy general name' ),
			'singular_name' => _x( 'Disciplina', 'taxonomy singular name' ),
			'search_items' =>  __( 'Busca Disciplinas' ),
			'popular_items' => __( 'Disciplinas Populares' ),
			'all_items' => __( 'Todos las Disciplinas' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Editar disciplina' ),
			'update_item' => __( 'Actualizar disciplina' ),
			'add_new_item' => __( 'Agregar nueva disciplina' ),
			'new_item_name' => __( 'Nuevo nombre de disciplina' ),
			'separate_items_with_commas' => __( 'Separar disciplinas con commas' ),
			'add_or_remove_items' => __( 'Agregar o quitar disciplinas' ),
			'choose_from_most_used' => __( 'Escoge de las disciplinas más usadas' ),
			'menu_name' => __( 'Disciplinas' ),
		);

		register_taxonomy('disciplinas',array('agenda', 'convocatorias', 'talleres'),array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'show_in_quick_edit' => false,
			'meta_box_cb' => false,
			'show_admin_column' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array( 'slug' => 'disciplinas' ),
		));

	}








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
			'menu_position'       => 4,
			'can_export'          => true,
			'has_archive'         => false,
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
			'taxonomies'          => array( 'post_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => false,
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
				'view_item'           => __( 'Ver colección'/* , 'montana' */ ),
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
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => false,
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
				'edit_item'           => __( 'Editar convocatoria'/* , 'montana' */ ),
				'update_item'         => __( 'Actualizar convocatoria'/* , 'montana' */ ),
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
			'menu_position'       => 5,
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
			'menu_position'       => 5,
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
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			// 'capability_type'     => 'page',
		);

		register_post_type( 'exposiciones', $args );



		$args = array(
			'label'               => __( 'servicios'),
			'description'         => __( 'Listado de Servicios'),
			'labels'              => array(
				'name'                => _x( 'Servicio permanente', 'Post Type General Name' ),
				'singular_name'       => _x( 'Servicios permanentes', 'Post Type Singular Name' ),
				'menu_name'           => __( 'Servicios' ),
				// 'parent_item_colon'   => __( 'Servicio Padre' ),
				'all_items'           => __( 'Todos los servicios permanentes' ),
				'view_item'           => __( 'Ver servicio permanente' ),
				'add_new_item'        => __( 'Agregar nuevo servicio permanente' ),
				'add_new'             => __( 'Agregar nuevo' ),
				'edit_item'           => __( 'Editar servicio permanente' ),
				'update_item'         => __( 'Actualizar servicio permanente' ),
				'search_items'        => __( 'Buscar servicio permanente' ),
				'not_found'           => __( 'No se encontró' ),
				'not_found_in_trash'  => __( 'No se encontró en la basura' ),
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
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			// 'capability_type'     => 'page',
		);

		register_post_type( 'servicios', $args );

	}

	add_action( 'init', 'custom_post_types', 0 );











/*
 *  Allow [.svg]
 */

	function cc_mime_types( $mimes ){
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
	add_filter( 'upload_mimes', 'cc_mime_types' );









/* Google API key */
	function my_acf_init() {
		acf_update_setting('google_api_key', 'AIzaSyCG3l_pG-5BMKnGDpYenf_eUgVSy0wtPes');
	}
	add_action('acf/init', 'my_acf_init');










/*
 *  WYSIWYG Mods
 */

	/*	COLORS  */
	function my_mce4_new_colors( $init ) {
		$default_colours = '';
		$custom_colours = ' "d93636", "Red", "f57b20", "Orange", "00aacd", "Blue", "FFFFFF", "White" ';
		$init['textcolor_map'] = '['.$custom_colours.','.$default_colours.']';
		return $init;
	}
	add_filter('tiny_mce_before_init', 'my_mce4_new_colors');


	/*	ACF  */
	function my_toolbars( $toolbars ) {

		$toolbars['Basic'][1] = array('forecolor' , 'bold' , 'italic' , 'link' , 'unlink' , 'removeformat' );

		// $toolbars['Normal' ] = array();
		// $toolbars['Normal' ][1] = array('styleselect' , 'bold' , 'forecolor' , 'alignleft aligncenter' , 'bullist' , 'indent' , 'outdent' , 'link' , 'unlink' , 'removeformat');

		// remove the 'Full' toolbar completely
		// unset( $toolbars['Full' ] );
		// unset( $toolbars['Basic' ] );

		return $toolbars;
	}
	add_filter( 'acf/fields/wysiwyg/toolbars' , 'my_toolbars'  );




/*
 * API Request in order
 */
	/* Add meta_key + meta_value_num to API */
	add_filter('rest_endpoints', function ($routes) {

		// I'm modifying multiple types here, you won't need the loop if you're just doing posts
		foreach (['agenda', 'cineteca', 'exposiciones', 'talleres'] as $type) {
			if (!($route =& $routes['/wp/v2/' . $type])) {
				continue;
			}

			// Allow ordering by my meta value
			$route[0]['args']['orderby']['enum'][] = 'meta_value_num';

			// Allow only the meta keys that I want
			$route[0]['args']['meta_key'] = array(
				'description'       => 'The meta key to query.',
				'type'              => 'string',
				'enum'              => ['order_day'],
				'validate_callback' => 'rest_validate_request_arg',
			);
		}
		return $routes;
	});

	add_filter('rest_agenda_query', function ($args, $request) {
		if ($key = $request->get_param('meta_key')) {
			$args['meta_key'] = $key;
		}
		return $args;
	}, 10, 2);

	add_filter('rest_cineteca_query', function ($args, $request) {
		if ($key = $request->get_param('meta_key')) {
			$args['meta_key'] = $key;
		}
		return $args;
	}, 10, 2);

	add_filter('rest_exposiciones_query', function ($args, $request) {
		if ($key = $request->get_param('meta_key')) {
			$args['meta_key'] = $key;
		}
		return $args;
	}, 10, 2);

	add_filter('rest_talleres_query', function ($args, $request) {
		if ($key = $request->get_param('meta_key')) {
			$args['meta_key'] = $key;
		}
		return $args;
	}, 10, 2);









/*  The End  */
