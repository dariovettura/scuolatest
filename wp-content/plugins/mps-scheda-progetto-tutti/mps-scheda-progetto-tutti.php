<?php
/**
 * Plugin Name: MPS Scheda Progetto – tutti i progetti
 * Description: Su /scheda-progetto/ mostra tutti i progetti (niente filtro anno scolastico) rispettando ordinamento e direzione da Configurazione > Didattica. Nasconde i pulsanti "anno in corso / anni scorsi".
 * Version: 1.0.0
 * Author: MyPortal School
 * Text Domain: mps-scheda-progetto-tutti
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MPS_SPT_VERSION', '1.0.0' );
define( 'MPS_SPT_FILE', __FILE__ );
define( 'MPS_SPT_URL', plugin_dir_url( __FILE__ ) );

/**
 * Sostituisce il filtro del tema dopo che è stato registrato.
 */
add_action( 'after_setup_theme', 'mps_spt_replace_schede_progetti_filter', 20 );

function mps_spt_replace_schede_progetti_filter() {
	if ( function_exists( 'dsi_schede_progetti_filters' ) ) {
		remove_action( 'pre_get_posts', 'dsi_schede_progetti_filters' );
	}

	add_action( 'pre_get_posts', 'mps_spt_schede_progetti_filters' );
}

/**
 * Ordina i progetti come da opzioni Didattica, senza filtrare per anno scolastico.
 *
 * @param WP_Query $query Query corrente.
 */
function mps_spt_schede_progetti_filters( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$is_archive = $query->is_post_type_archive( 'scheda_progetto' );
	$is_tax     = $query->is_tax( 'tipologia-progetto' );

	if ( ! $is_archive && ! $is_tax ) {
		return;
	}

	if ( ! function_exists( 'dsi_get_option' ) ) {
		return;
	}

	$orderby         = dsi_get_option( 'ordinamento_progetti', 'didattica' ) ?? 'date';
	$order_direction = dsi_get_option( 'direzione_ordinamento_progetti', 'didattica' ) === 'asc' ? 'asc' : 'desc';

	switch ( $orderby ) {
		case 'timestamp_inizio':
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'meta_key', '_dsi_scheda_progetto_timestamp_inizio' );
			break;
		case 'timestamp_fine':
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'meta_key', '_dsi_scheda_progetto_timestamp_fine' );
			break;
		case 'anno_scolastico':
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'meta_key', '_dsi_scheda_progetto_anno_scolastico' );
			break;
		case 'title':
		case 'date':
			$query->set( 'orderby', $orderby );
			break;
		case 'realizzato':
		default:
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_key', '_dsi_scheda_progetto_is_realizzato' );
			break;
	}

	$query->set( 'order', $order_direction );
	// Nessun meta_query su anno scolastico: elenco completo anche con ?archive=true.
}

/**
 * CSS: nasconde i pulsanti archive solo sull'archivio progetti.
 */
add_action( 'wp_enqueue_scripts', 'mps_spt_enqueue_styles' );

function mps_spt_enqueue_styles() {
	if ( ! is_post_type_archive( 'scheda_progetto' ) ) {
		return;
	}

	wp_enqueue_style(
		'mps-scheda-progetto-tutti',
		MPS_SPT_URL . 'assets/hide-archive-buttons.css',
		array(),
		MPS_SPT_VERSION
	);
}
