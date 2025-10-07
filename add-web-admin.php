<?php
////////////////////////////////////////////////////////////////////
//Aggiunta ruolo web Admin:
// nella prima funzione andiamo a creare il nuovo utente con tutti i permessi utente a true
// nella seconda impediamo di creare utenti amministratori
// nella terza invalidiamo le modifiche a un user amministratore
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
		'manage_categories' => true,
		'moderate_comments' => true,
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


function wporg_limit_admin_roles($all_roles) {

	if (current_user_can('web_admin') && !current_user_can('administrator')) {
		unset($all_roles['administrator']);
	}
	return $all_roles;
}
add_filter('editable_roles', 'wporg_limit_admin_roles');


function wporg_prevent_web_admin_modifying_admins($user_id) {

  $current_user = wp_get_current_user();
  if (in_array('web_admin', $current_user->roles)) {
      $user_to_edit = get_user_by('id', $user_id);
      if (in_array('administrator', $user_to_edit->roles)) {
          wp_die('Non puoi modificare o eliminare utenti con il ruolo di "Administrator".');
      }
  }
}
add_action('delete_user', 'wporg_prevent_web_admin_modifying_admins');
add_action('edit_user_profile_update', 'wporg_prevent_web_admin_modifying_admins');