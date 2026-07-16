<?php

/**
 * Override robusto delle utility del parent:
 * - evita check Members con post_id vuoto
 * - usa il prefisso in base al post type reale (non is_singular corrente)
 */
if ( ! function_exists( 'dsi_members_can_user_view_post' ) ) {
	function dsi_members_can_user_view_post( $user_id, $post_id ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		if ( ! function_exists( 'members_can_user_view_post' ) ) {
			return true;
		}

		return members_can_user_view_post( $user_id, $post_id );
	}
}

if ( ! function_exists( 'dsi_get_meta' ) ) {
	function dsi_get_meta( $key = '', $prefix = '', $post_id = '' ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		if ( ! dsi_members_can_user_view_post( get_current_user_id(), $post_id ) ) {
			return false;
		}

		if ( $prefix !== '' ) {
			return get_post_meta( $post_id, $prefix . $key, true );
		}

		$post_type = get_post_type( $post_id );
		if ( ! $post_type ) {
			return get_post_meta( $post_id, $key, true );
		}

		// Eccezione: il post type `post` viene memorizzato con prefisso `_dsi_articolo_`.
		if ( $post_type === 'post' ) {
			$computed_prefix = '_dsi_articolo_';
		} else {
			$computed_prefix = '_dsi_' . $post_type . '_';
		}

		return get_post_meta( $post_id, $computed_prefix . $key, true );
	}
}

////////////////////////////////////////////////////////////////////
// Blocco x-frame - X-XSS-Protection - X-Content-Type-Options
////////////////////////////////////////////////////////////////////

function block_frames() {
header( 'X-FRAME-OPTIONS: SAMEORIGIN' );
}
add_action( 'send_headers', 'block_frames', 10 );
	header('X-XSS-Protection: 1; mode=block');
	header('X-Content-Type-Options: nosniff');
	header('Referrer-Policy: no-referrer-when-downgrade');
	header("Content-Security-Policy: default-src 'self'");
	header("Content-Security-Policy: script-src 'self'");
	header("Content-Security-Policy: connect-src 'self'");
	header("Permissions-Policy: geolocation=*; camera=();");
	header('x-powered-by: 3D Solution');

function remove_header_info() {
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'start_post_rel_link');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'adjacent_posts_rel_link');
	remove_action('wp_head', 'x-powered-by');
	remove_action('wp_head', 'server');
	}
add_action('init', 'remove_header_info');


////////////////////////////////////////////////////////////////////
// disable Gutenberg 
////////////////////////////////////////////////////////////////////

add_filter('use_block_editor_for_post', '__return_false' );


////////////////////////////////////////////////////////////////////
// disable author archives
////////////////////////////////////////////////////////////////////
function shapeSpace_disable_author_archives() {
	if (is_author()) {
		global $wp_query;
		$wp_query->set_404();
		status_header(404);
	} else {
		redirect_canonical();
	}
}
remove_filter('template_redirect', 'redirect_canonical');
add_action('template_redirect', 'shapeSpace_disable_author_archives');


////////////////////////////////////////////////////////////////////
//Disattiva scansione enumerazione utenti
////////////////////////////////////////////////////////////////////
/*
// block WP enum scans
// https://m0n.co/enum
if (!is_admin()) {
	// default URL format
	if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) die();
	add_filter('redirect_canonical', 'shapeSpace_check_enum', 10, 2);
}
function shapeSpace_check_enum($redirect, $request) {
	// permalink URL format
	if (preg_match('/\?author=([0-9]*)(\/*)/i', $request)) die();
	else return $redirect;
}
*/

////////////////////////////////////////////////////////////////////
//Cambio logo
////////////////////////////////////////////////////////////////////

function my_login_logo() {?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/Logo_3D_MyPortal.png);
		height:92px;
		width:320px;
		background-size: 320px 92px;
		background-repeat: no-repeat;
        	padding-bottom: 5px;
        }
    </style>
<?php }

add_action( 'login_enqueue_scripts', 'my_login_logo' );
function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'Ritorna alla home';
}
add_filter( 'login_headertext', 'my_login_logo_url_title' );

////////////////////////////////////////////////////////////////////
//Aggiunta CSS Loghi APP
////////////////////////////////////////////////////////////////////
wp_enqueue_style('marketplaces_css', get_stylesheet_directory_uri() . '/assets/css/marketplaces.css');

////////////////////////////////////////////////////////////////////
//Aggiunta caricamento estensioni
////////////////////////////////////////////////////////////////////

function wpdocs_add_p7m( $wp_get_mime_types ) {
	$wp_get_mime_types['p7m'] = 'application/p7m';
	return $wp_get_mime_types;
}

add_filter( 'mime_types', 'wpdocs_add_p7m' );


////////////////////////////////////////////////////////////////////
// Register the Sidebar(s) HOME Centrale
////////////////////////////////////////////////////////////////////
if ( function_exists('register_sidebar') )
register_sidebar( array(
		'name'          => esc_html__( 'Home Centrale News', 'design_scuole_italia' ),
		'id'            => 'home-centrale-news',
		'description'   => esc_html__( 'home centrale news', 'design_scuole_italia' ),
		'before_widget' => '<aside id="%1$s" class="container">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="h3">',
		'after_title'   => '</h2>',
	) );
	
	
get_template_part('dynamic_sidebar') || !dynamic_sidebar('Home Centrale News');

if ( function_exists('register_sidebar') )
register_sidebar( array(
		'name'          => esc_html__( 'Home Centrale Servizi', 'design_scuole_italia' ),
		'id'            => 'home-centrale-servizi',
		'description'   => esc_html__( 'home centrale servizi', 'design_scuole_italia' ),
		'before_widget' => '<aside id="%1$s" class="container">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="h3">',
		'after_title'   => '</h2>',
	) );
	
	
get_template_part('dynamic_sidebar') || !dynamic_sidebar('Home Centrale Servizi');

if ( function_exists('register_sidebar') )
register_sidebar( array(
		'name'          => esc_html__( 'Home Centrale Didattica', 'design_scuole_italia' ),
		'id'            => 'home-centrale-didattica',
		'description'   => esc_html__( 'home centrale didattica', 'design_scuole_italia' ),
		'before_widget' => '<aside id="%1$s" class="container">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="h3">',
		'after_title'   => '</h2>',
	) );
	
	
get_template_part('dynamic_sidebar') || !dynamic_sidebar('Home Centrale Didattica');

////////////////////////////////////////////////////////////////////
//Removes Menu Ricarica Dati
////////////////////////////////////////////////////////////////////

function hide_reload_data() {
    remove_submenu_page('themes.php', 'reload-data-theme-options');
}
add_action('admin_init', 'hide_reload_data');
/*
////////////////////////////////////////////////////////////////////
//Removes litespeed some menus by page.
////////////////////////////////////////////////////////////////////

function plt_hide_litespeed_cache_menus() {
	//Hide "Settings → LiteSpeed Cache".
	remove_submenu_page('options-general.php', 'litespeed-cache-options');

	//Hide "LiteSpeed Cache".
	remove_menu_page('litespeed');
	//Hide "LiteSpeed Cache → Dashboard".
	remove_submenu_page('litespeed', 'litespeed');
	//Hide "LiteSpeed Cache → Presets".
	remove_submenu_page('litespeed', 'litespeed-presets');
	//Hide "LiteSpeed Cache → General".
	remove_submenu_page('litespeed', 'litespeed-general');
	//Hide "LiteSpeed Cache → Cache".
	remove_submenu_page('litespeed', 'litespeed-cache');
	//Hide "LiteSpeed Cache → CDN".
	remove_submenu_page('litespeed', 'litespeed-cdn');
	//Hide "LiteSpeed Cache → Image Optimization".
	remove_submenu_page('litespeed', 'litespeed-img_optm');
	//Hide "LiteSpeed Cache → Page Optimization".
	remove_submenu_page('litespeed', 'litespeed-page_optm');
	//Hide "LiteSpeed Cache → Database".
	remove_submenu_page('litespeed', 'litespeed-db_optm');
	//Hide "LiteSpeed Cache → Crawler".
	remove_submenu_page('litespeed', 'litespeed-crawler');
	//Hide "LiteSpeed Cache → Toolbox".
	remove_submenu_page('litespeed', 'litespeed-toolbox');
}

add_action('admin_menu', 'plt_hide_litespeed_cache_menus', 11);
*/
////////////////////////////////////////////////////////////////////
//Removes ADC some menus by page.
////////////////////////////////////////////////////////////////////
function wpadc_remove_menus() {

    remove_menu_page('edit.php?post_type=acf-field-group'); //Pages
}
add_action('admin_menu', 'wpadc_remove_menus');

////////////////////////////////////////////////////////////////////
//Aggiunta ruolo web editor
////////////////////////////////////////////////////////////////////

function wporg_web_editor_role() {
	add_role(
		'web_editor',
		'Web Editor',
		array(
//Generali			
		'manage_categories' => true,
		'manage_links' => true,
		'moderate_comments' => true,
		'read' => true,
		'unfiltered_html' => true,
		'upload_files' => true,
		'edit_dashboard' => false,
		'export' => false,
		'import' => false,
		'install_languages' => false,
		'manage_options' => false,
		'unfiltered_upload' => false,
		'update_core' => false,
		'view_site_health_checks' => false,
//Temi
		'edit_theme_options' => true,
		'delete_themes' => false,
		'edit_themes' => false,
		'install_themes' => false,
		'resume_themes' => false,
		'switch_themes' => false,
		'update_themes' => false,
//Articoli
		'delete_others_posts' => true,
		'delete_posts' => true,
		'delete_private_posts' => true,
		'delete_published_posts' => true,
		'edit_others_posts' => true,
		'edit_posts' => true,
		'edit_private_posts' => true,
		'edit_published_posts' => true,
		'publish_posts' => true,
		'read_private_posts' => true,
//Pagine		
		'delete_others_pages' => true,
		'delete_pages' => true,
		'delete_private_pages' => true,
		'delete_published_pages' => true,
		'edit_others_pages' => true,
		'edit_pages' => true,
		'edit_private_pages' => true,
		'edit_published_pages' => true,
		'publish_pages' => true,
		'read_private_pages' => true,
//Plugin
		'activate_plugins' => false,
		'delete_plugins' => false,
		'edit_plugins' => false,
		'install_plugins' => false,
		'resume_plugins' => false,
		'update_plugins' => false,
//Utenti-Persone		
		'create_users' => false,
		'delete_users' => false,
		'edit_users' => false,
		'list_users' => false,
		'promote_users' => false,
		'remove_users' => false,
//Contenuto personalizzato del Tema Design Scuole Italia
	//Indirizzo di Studio
		'delete_indirizzi_di_studio' => true,
		'delete_others_indirizzi_di_studio' => true,
		'delete_private_indirizzi_di_studio' => true,
		'delete_published_indirizzi_di_studio' => true,
		'edit_indirizzi_di_studio' => true,
		'edit_others_indirizzi_di_studio' => true,
		'edit_private_indirizzi_di_studio' => true,
		'edit_published_indirizzi_di_studio' => true,
		'publish_indirizzi_di_studio' => true,
		'read_private_indirizzi_di_studio' => true,
	//Luoghi
		'delete_luoghi' => true,
		'delete_others_luoghi' => true,
		'delete_private_luoghi' => true,
		'delete_published_luoghi' => true,
		'edit_luoghi' => true,
		'edit_others_luoghi' => true,
		'edit_private_luoghi' => true,
		'edit_published_luoghi' => true,
		'publish_luoghi' => true,
		'read_private_luoghi' => true,
	//Servizi
		'delete_others_servizi' => true,
		'delete_private_servizi' => true,
		'delete_published_servizi' => true,
		'delete_servizi' => true,
		'edit_others_servizi' => true,
		'edit_private_servizi' => true,
		'edit_published_servizi' => true,
		'edit_servizi' => true,
		'publish_servizi' => true,
		'read_private_servizi' => true,
	//Srutture
		'delete_others_strutture' => true,
		'delete_private_strutture' => true,
		'delete_published_strutture' => true,
		'delete_strutture' => true,
		'edit_others_strutture' => true,
		'edit_private_strutture' => true,
		'edit_published_strutture' => true,
		'edit_strutture' => true,
		'publish_strutture' => true,
		'read_private_strutture' => true,
	//Eventi
		'delete_eventi' => true,
		'delete_others_eventi' => true,
		'delete_private_eventi' => true,
		'delete_published_eventi' => true,
		'edit_eventi' => true,
		'edit_others_eventi' => true,
		'edit_private_eventi' => true,
		'edit_published_eventi' => true,
		'publish_eventi' => true,
		'read_private_eventi' => true,
	//Schede Didattiche
		'delete_others_schede_didattica' => true,
		'delete_private_schede_didattica' => true,
		'delete_published_schede_didattica' => true,
		'delete_schede_didattica' => true,
		'edit_others_schede_didattica' => true,
		'edit_private_schede_didattica' => true,
		'edit_published_schede_didattica' => true,
		'edit_schede_didattica' => true,
		'publish_schede_didattica' => true,
		'read_private_schede_didattica' => true,
	//Schede Progetti
		'delete_others_schede_progetto' => true,
		'delete_private_schede_progetto' => true,
		'delete_published_schede_progetto' => true,
		'delete_schede_progetto' => true,
		'edit_others_schede_progetto' => true,
		'edit_private_schede_progetto' => true,
		'edit_published_schede_progetto' => true,
		'edit_schede_progetto' => true,
		'publish_schede_progetto' => true,
		'read_private_schede_progetto' => true,
	//Circolari
		'delete_circolari' => true,
		'delete_others_circolari' => true,
		'delete_private_circolari' => true,
		'delete_published_circolari' => true,
		'edit_circolari' => true,
		'edit_others_circolari' => true,
		'edit_private_circolari' => true,
		'edit_published_circolari' => true,
		'publish_circolari' => true,
		'read_private_circolari' => true,
	//Documenti
		'delete_documenti' => true,
		'delete_others_documenti' => true,
		'delete_private_documenti' => true,
		'delete_published_documenti' => true,
		'edit_documenti' => true,
		'edit_others_documenti' => true,
		'edit_private_documenti' => true,
		'edit_published_documenti' => true,
		'publish_documenti' => true,
		'read_private_documenti' => true,
	//Tipologia		
		'assign_tipologia_articoli' => true,
		'assign_tipologia_circolare' => true,
		'assign_tipologia_documenti' => true,
		'assign_tipologia_eventi' => true,
		'assign_tipologia_luoghi' => true,
		'assign_tipologia_progetti' => true,
		'assign_tipologia_servizi' => true,
		'assign_tipologia_strutture' => true,

		'delete_tipologia_articoli' => true,
		'delete_tipologia_circolare' => true,
		'delete_tipologia_documenti' => true,
		'delete_tipologia_eventi' => true,
		'delete_tipologia_luoghi' => true,
		'delete_tipologia_progetti' => true,
		'delete_tipologia_servizi' => true,
		'delete_tipologia_strutture' => true,

		'edit_tipologia_articoli' => true,
		'edit_tipologia_circolare' => true,
		'edit_tipologia_documenti' => true,
		'edit_tipologia_eventi' => true,
		'edit_tipologia_luoghi' => true,
		'edit_tipologia_progetti' => true,
		'edit_tipologia_servizi' => true,
		'edit_tipologia_strutture' => true,
			
		'manage_percorsi-di-studio' => true,
		'manage_tipologia_articoli' => true,
		'manage_tipologia_circolare' => true,
		'manage_tipologia_documenti' => true,
		'manage_tipologia_eventi' => true,
		'manage_tipologia_luoghi' => true,
		'manage_tipologia_progetti' => true,
		'manage_tipologia_servizi' => true,
		'manage_tipologia_strutture' => true,
		),
	);
}
			

// Add the simple_role.
add_action( 'init', 'wporg_web_editor_role' );


////////////////////////////////////////////////////////////////////
//Aggiunta ruolo web Manager
////////////////////////////////////////////////////////////////////

function wporg_web_manager_role() {
	add_role(
		'web_manager',
		'Web Manager',
		array(
//Configurazione			
		'dsi_options' => true,
//Generali			
		'manage_categories' => true,
		'manage_links' => true,
		'moderate_comments' => true,
		'read' => true,
		'unfiltered_html' => true,
		'upload_files' => true,
		'edit_dashboard' => false,
		'export' => false,
		'import' => false,
		'install_languages' => false,
		'manage_options' => true,
		'unfiltered_upload' => false,
		'update_core' => false,
		'view_site_health_checks' => false,
//Temi
		'edit_theme_options' => true,
		'delete_themes' => false,
		'edit_themes' => false,
		'install_themes' => false,
		'resume_themes' => false,
		'switch_themes' => false,
		'update_themes' => false,
//Articoli
		'delete_others_posts' => true,
		'delete_posts' => true,
		'delete_private_posts' => true,
		'delete_published_posts' => true,
		'edit_others_posts' => true,
		'edit_posts' => true,
		'edit_private_posts' => true,
		'edit_published_posts' => true,
		'publish_posts' => true,
		'read_private_posts' => true,
//Pagine		
		'delete_others_pages' => true,
		'delete_pages' => true,
		'delete_private_pages' => true,
		'delete_published_pages' => true,
		'edit_others_pages' => true,
		'edit_pages' => true,
		'edit_private_pages' => true,
		'edit_published_pages' => true,
		'publish_pages' => true,
		'read_private_pages' => true,
//Plugin
		'activate_plugins' => false,
		'delete_plugins' => false,
		'edit_plugins' => false,
		'install_plugins' => false,
		'resume_plugins' => false,
		'update_plugins' => false,
//Utenti-Persone		
		'create_users' => false,
		'delete_users' => false,
		'edit_users' => false,
		'list_users' => false,
		'promote_users' => false,
		'remove_users' => false,
//Contenuto personalizzato del Tema Design Scuole Italia
	//Indirizzo di Studio
		'delete_indirizzi_di_studio' => true,
		'delete_others_indirizzi_di_studio' => true,
		'delete_private_indirizzi_di_studio' => true,
		'delete_published_indirizzi_di_studio' => true,
		'edit_indirizzi_di_studio' => true,
		'edit_others_indirizzi_di_studio' => true,
		'edit_private_indirizzi_di_studio' => true,
		'edit_published_indirizzi_di_studio' => true,
		'publish_indirizzi_di_studio' => true,
		'read_private_indirizzi_di_studio' => true,
	//Luoghi
		'delete_luoghi' => true,
		'delete_others_luoghi' => true,
		'delete_private_luoghi' => true,
		'delete_published_luoghi' => true,
		'edit_luoghi' => true,
		'edit_others_luoghi' => true,
		'edit_private_luoghi' => true,
		'edit_published_luoghi' => true,
		'publish_luoghi' => true,
		'read_private_luoghi' => true,
	//Servizi
		'delete_others_servizi' => true,
		'delete_private_servizi' => true,
		'delete_published_servizi' => true,
		'delete_servizi' => true,
		'edit_others_servizi' => true,
		'edit_private_servizi' => true,
		'edit_published_servizi' => true,
		'edit_servizi' => true,
		'publish_servizi' => true,
		'read_private_servizi' => true,
	//Srutture
		'delete_others_strutture' => true,
		'delete_private_strutture' => true,
		'delete_published_strutture' => true,
		'delete_strutture' => true,
		'edit_others_strutture' => true,
		'edit_private_strutture' => true,
		'edit_published_strutture' => true,
		'edit_strutture' => true,
		'publish_strutture' => true,
		'read_private_strutture' => true,
	//Eventi
		'delete_eventi' => true,
		'delete_others_eventi' => true,
		'delete_private_eventi' => true,
		'delete_published_eventi' => true,
		'edit_eventi' => true,
		'edit_others_eventi' => true,
		'edit_private_eventi' => true,
		'edit_published_eventi' => true,
		'publish_eventi' => true,
		'read_private_eventi' => true,
	//Schede Didattiche
		'delete_others_schede_didattica' => true,
		'delete_private_schede_didattica' => true,
		'delete_published_schede_didattica' => true,
		'delete_schede_didattica' => true,
		'edit_others_schede_didattica' => true,
		'edit_private_schede_didattica' => true,
		'edit_published_schede_didattica' => true,
		'edit_schede_didattica' => true,
		'publish_schede_didattica' => true,
		'read_private_schede_didattica' => true,
	//Schede Progetti
		'delete_others_schede_progetto' => true,
		'delete_private_schede_progetto' => true,
		'delete_published_schede_progetto' => true,
		'delete_schede_progetto' => true,
		'edit_others_schede_progetto' => true,
		'edit_private_schede_progetto' => true,
		'edit_published_schede_progetto' => true,
		'edit_schede_progetto' => true,
		'publish_schede_progetto' => true,
		'read_private_schede_progetto' => true,
	//Circolari
		'delete_circolari' => true,
		'delete_others_circolari' => true,
		'delete_private_circolari' => true,
		'delete_published_circolari' => true,
		'edit_circolari' => true,
		'edit_others_circolari' => true,
		'edit_private_circolari' => true,
		'edit_published_circolari' => true,
		'publish_circolari' => true,
		'read_private_circolari' => true,
	//Documenti
		'delete_documenti' => true,
		'delete_others_documenti' => true,
		'delete_private_documenti' => true,
		'delete_published_documenti' => true,
		'edit_documenti' => true,
		'edit_others_documenti' => true,
		'edit_private_documenti' => true,
		'edit_published_documenti' => true,
		'publish_documenti' => true,
		'read_private_documenti' => true, 
	//Tipologia		
		'assign_tipologia_articoli' => true,
		'assign_tipologia_circolare' => true,
		'assign_tipologia_documenti' => true,
		'assign_tipologia_eventi' => true,
		'assign_tipologia_luoghi' => true,
		'assign_tipologia_progetti' => true,
		'assign_tipologia_servizi' => true,
		'assign_tipologia_strutture' => true,

		'delete_tipologia_articoli' => true,
		'delete_tipologia_circolare' => true,
		'delete_tipologia_documenti' => true,
		'delete_tipologia_eventi' => true,
		'delete_tipologia_luoghi' => true,
		'delete_tipologia_progetti' => true,
		'delete_tipologia_servizi' => true,
		'delete_tipologia_strutture' => true,

		'edit_tipologia_articoli' => true,
		'edit_tipologia_circolare' => true,
		'edit_tipologia_documenti' => true,
		'edit_tipologia_eventi' => true,
		'edit_tipologia_luoghi' => true,
		'edit_tipologia_progetti' => true,
		'edit_tipologia_servizi' => true,
		'edit_tipologia_strutture' => true,
			
		'manage_percorsi-di-studio' => true,
		'manage_tipologia_articoli' => true,
		'manage_tipologia_circolare' => true,
		'manage_tipologia_documenti' => true,
		'manage_tipologia_eventi' => true,
		'manage_tipologia_luoghi' => true,
		'manage_tipologia_progetti' => true,
		'manage_tipologia_servizi' => true,
		'manage_tipologia_strutture' => true,
		),
	);
}

// Add the simple_role.
add_action( 'init', 'wporg_web_manager_role' );
/*
$wp_roles->remove_role("web_manager");
$wp_roles->remove_role("web_editor");
*/



////////////////////////////////////////////////////////////////////
//Aggiunta ruolo web Admin
////////////////////////////////////////////////////////////////////

function wporg_web_admin_role() {
	add_role(
		'web_admin',
		'Web Admin',
		array(
//Configurazione			
		'dsi_options' => true,
//Generali			
		'manage_categories' => true,
		'manage_links' => true,
		'moderate_comments' => true,
		'read' => true,
		'unfiltered_html' => true,
		'upload_files' => true,
		'edit_dashboard' => false,
		'export' => false,
		'import' => false,
		'install_languages' => false,
		'manage_options' => true,
		'unfiltered_upload' => false,
		'update_core' => false,
		'view_site_health_checks' => false,
//Temi
		'edit_theme_options' => true,
		'delete_themes' => false,
		'edit_themes' => false,
		'install_themes' => false,
		'resume_themes' => false,
		'switch_themes' => false,
		'update_themes' => false,
//Articoli
		'delete_others_posts' => true,
		'delete_posts' => true,
		'delete_private_posts' => true,
		'delete_published_posts' => true,
		'edit_others_posts' => true,
		'edit_posts' => true,
		'edit_private_posts' => true,
		'edit_published_posts' => true,
		'publish_posts' => true,
		'read_private_posts' => true,
//Pagine		
		'delete_others_pages' => true,
		'delete_pages' => true,
		'delete_private_pages' => true,
		'delete_published_pages' => true,
		'edit_others_pages' => true,
		'edit_pages' => true,
		'edit_private_pages' => true,
		'edit_published_pages' => true,
		'publish_pages' => true,
		'read_private_pages' => true,
//Plugin
		'activate_plugins' => false,
		'delete_plugins' => false,
		'edit_plugins' => false,
		'install_plugins' => false,
		'resume_plugins' => false,
		'update_plugins' => false,
//Utenti-Persone		
		'create_users' => true,
		'delete_users' => true,
		'edit_users' => true,
		'list_users' => true,
		'promote_users' => true,
		'promote_user' => true,
		'remove_users' => true,
//Contenuto personalizzato del Tema Design Scuole Italia
	//Indirizzo di Studio
		'delete_indirizzi_di_studio' => true,
		'delete_others_indirizzi_di_studio' => true,
		'delete_private_indirizzi_di_studio' => true,
		'delete_published_indirizzi_di_studio' => true,
		'edit_indirizzi_di_studio' => true,
		'edit_others_indirizzi_di_studio' => true,
		'edit_private_indirizzi_di_studio' => true,
		'edit_published_indirizzi_di_studio' => true,
		'publish_indirizzi_di_studio' => true,
		'read_private_indirizzi_di_studio' => true,
	//Luoghi
		'delete_luoghi' => true,
		'delete_others_luoghi' => true,
		'delete_private_luoghi' => true,
		'delete_published_luoghi' => true,
		'edit_luoghi' => true,
		'edit_others_luoghi' => true,
		'edit_private_luoghi' => true,
		'edit_published_luoghi' => true,
		'publish_luoghi' => true,
		'read_private_luoghi' => true,
	//Servizi
		'delete_others_servizi' => true,
		'delete_private_servizi' => true,
		'delete_published_servizi' => true,
		'delete_servizi' => true,
		'edit_others_servizi' => true,
		'edit_private_servizi' => true,
		'edit_published_servizi' => true,
		'edit_servizi' => true,
		'publish_servizi' => true,
		'read_private_servizi' => true,
	//Srutture
		'delete_others_strutture' => true,
		'delete_private_strutture' => true,
		'delete_published_strutture' => true,
		'delete_strutture' => true,
		'edit_others_strutture' => true,
		'edit_private_strutture' => true,
		'edit_published_strutture' => true,
		'edit_strutture' => true,
		'publish_strutture' => true,
		'read_private_strutture' => true,
	//Eventi
		'delete_eventi' => true,
		'delete_others_eventi' => true,
		'delete_private_eventi' => true,
		'delete_published_eventi' => true,
		'edit_eventi' => true,
		'edit_others_eventi' => true,
		'edit_private_eventi' => true,
		'edit_published_eventi' => true,
		'publish_eventi' => true,
		'read_private_eventi' => true,
	//Schede Didattiche
		'delete_others_schede_didattica' => true,
		'delete_private_schede_didattica' => true,
		'delete_published_schede_didattica' => true,
		'delete_schede_didattica' => true,
		'edit_others_schede_didattica' => true,
		'edit_private_schede_didattica' => true,
		'edit_published_schede_didattica' => true,
		'edit_schede_didattica' => true,
		'publish_schede_didattica' => true,
		'read_private_schede_didattica' => true,
	//Schede Progetti
		'delete_others_schede_progetto' => true,
		'delete_private_schede_progetto' => true,
		'delete_published_schede_progetto' => true,
		'delete_schede_progetto' => true,
		'edit_others_schede_progetto' => true,
		'edit_private_schede_progetto' => true,
		'edit_published_schede_progetto' => true,
		'edit_schede_progetto' => true,
		'publish_schede_progetto' => true,
		'read_private_schede_progetto' => true,
	//Circolari
		'delete_circolari' => true,
		'delete_others_circolari' => true,
		'delete_private_circolari' => true,
		'delete_published_circolari' => true,
		'edit_circolari' => true,
		'edit_others_circolari' => true,
		'edit_private_circolari' => true,
		'edit_published_circolari' => true,
		'publish_circolari' => true,
		'read_private_circolari' => true,
	//Documenti
		'delete_documenti' => true,
		'delete_others_documenti' => true,
		'delete_private_documenti' => true,
		'delete_published_documenti' => true,
		'edit_documenti' => true,
		'edit_others_documenti' => true,
		'edit_private_documenti' => true,
		'edit_published_documenti' => true,
		'publish_documenti' => true,
		'read_private_documenti' => true, 
	//Tipologia		
		'assign_tipologia_articoli' => true,
		'assign_tipologia_circolare' => true,
		'assign_tipologia_documenti' => true,
		'assign_tipologia_eventi' => true,
		'assign_tipologia_luoghi' => true,
		'assign_tipologia_progetti' => true,
		'assign_tipologia_servizi' => true,
		'assign_tipologia_strutture' => true,

		'delete_tipologia_articoli' => true,
		'delete_tipologia_circolare' => true,
		'delete_tipologia_documenti' => true,
		'delete_tipologia_eventi' => true,
		'delete_tipologia_luoghi' => true,
		'delete_tipologia_progetti' => true,
		'delete_tipologia_servizi' => true,
		'delete_tipologia_strutture' => true,

		'edit_tipologia_articoli' => true,
		'edit_tipologia_circolare' => true,
		'edit_tipologia_documenti' => true,
		'edit_tipologia_eventi' => true,
		'edit_tipologia_luoghi' => true,
		'edit_tipologia_progetti' => true,
		'edit_tipologia_servizi' => true,
		'edit_tipologia_strutture' => true,
			
		'manage_percorsi-di-studio' => true,
		'manage_tipologia_articoli' => true,
		'manage_tipologia_circolare' => true,
		'manage_tipologia_documenti' => true,
		'manage_tipologia_eventi' => true,
		'manage_tipologia_luoghi' => true,
		'manage_tipologia_progetti' => true,
		'manage_tipologia_servizi' => true,
		'manage_tipologia_strutture' => true,
		),
	);
}

// Add the simple_role.
add_action( 'init', 'wporg_web_admin_role' );
function update_web_admin_role() {
	$role = get_role('web_admin');
	if ($role) {
			$role->add_cap('promote_users');
			$role->add_cap('promote_user');
	}
}
add_action('init', 'update_web_admin_role');


// Limitare i ruoli che il Web Admin può assegnare
function wporg_limit_admin_roles($all_roles) {
	// Verifica se l'utente corrente ha il ruolo "Web Admin"
	if (current_user_can('web_admin') && !current_user_can('administrator')) {
		// Rimuove il ruolo di amministratore dalla lista dei ruoli assegnabili
		unset($all_roles['administrator']);
	}
	return $all_roles;
}
add_filter('editable_roles', 'wporg_limit_admin_roles');

////////////////////////////////////////////////////////////////////
// Funzione per sostituire il breadcrumb esistente se clicco da Didattica -> Presentazione
// e da Didattica -> Offerta formativa
////////////////////////////////////////////////////////////////////

add_rewrite_rule(
    '^didattica/offerta-formativa/indirizzo/([^/]+)/?$',
    'index.php?indirizzo=$matches[1]',
    'top'
);
add_rewrite_rule(
    '^didattica/offerta-formativa/struttura/([^/]+)/?$',
    'index.php?struttura=$matches[1]',
    'top'
);
add_rewrite_rule(
    '^didattica/struttura/([^/]+)/?$',
    'index.php?struttura=$matches[1]',
    'top'
);

add_rewrite_rule(
    '^didattica/indirizzo/([^/]+)/?$',
    'index.php?indirizzo=$matches[1]',
    'top'
);



function custom_breadcrumb_items( $items, $args ) {
  // Verifica se la pagina proviene da "didattica/indirizzo/$nome"
  if (
      ( is_singular( 'indirizzo' ) && strpos( $_SERVER['REQUEST_URI'], '/didattica/indirizzo/' ) !== false ) ||
      ( is_singular( 'struttura' ) && strpos( $_SERVER['REQUEST_URI'], '/didattica/struttura/' ) !== false )
  ) {
      $items = array_slice( $items, 0, 1 );
      $items[] = '<a href="' . home_url( 'didattica' ) . '">' . __("Didattica", "design_scuole_italia") . '</a>';
      $items[] = '<span class="current">' . get_the_title() . '</span>';
  } elseif (
    ( is_singular( 'indirizzo' ) && strpos( $_SERVER['REQUEST_URI'], '/didattica/offerta-formativa/indirizzo/' ) !== false ) ||
    ( is_singular( 'struttura' ) && strpos( $_SERVER['REQUEST_URI'], '/didattica/offerta-formativa/struttura/' ) !== false )
) {
  $items = array_slice( $items, 0, 1 );
  $items[] = '<a href="' . home_url( 'didattica' ) . '">' . __("Didattica", "design_scuole_italia") . '</a>';
  $items[] = '<a href="' . home_url( 'didattica/offerta-formativa' ) . '">' . __("Offerta Formativa", "design_scuole_italia") . '</a>';
  $items[] = '<span class="current">' . get_the_title() . '</span>';
     
  }

  return $items;
}

add_filter( 'breadcrumb_trail_items', 'custom_breadcrumb_items', 10, 2 );


////////////////////////////////////////////////////////////////////
// Rewrite Permalink
////////////////////////////////////////////////////////////////////

function custom_rewrite_rules(){
    flush_rewrite_rules();
}
add_action('init', 'custom_rewrite_rules' );


////////////////////////////////////////////////////////////////////
// Always expire Post Password Session cookie
////////////////////////////////////////////////////////////////////
add_action( 'wp', 'post_pw_sess_expire' );
function post_pw_sess_expire() {
  if ( isset( $_COOKIE['wp-postpass_' . COOKIEHASH] ) )
  setcookie('wp-postpass_' . COOKIEHASH, '', 0, COOKIEPATH);
}





function custom_change_image_src($image, $attachment_id, $size, $icon) {
	// Aggiungi un log per vedere quando la funzione viene chiamata


	// Verifica se la dimensione è "vertical-card"
	if ($size === 'vertical-card') {
			// Modifica la dimensione a "full"
			error_log('Changing size from "vertical-card" to "full".');
			$image = wp_get_attachment_image_src($attachment_id, 'full');
	}

	// Log dell'URL finale
	error_log('Final image URL: ' . (isset($image[0]) ? $image[0] : 'No URL'));

	return $image;
}
add_filter('wp_get_attachment_image_src', 'custom_change_image_src', 10, 4);