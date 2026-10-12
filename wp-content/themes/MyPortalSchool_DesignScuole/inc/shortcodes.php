<?php
/**
 * Shortcode lista contenuti per argomento o post-type.
 *
 * Uso:
 * [mps_lista_contenuti tipo="argomento" argomento="nome-o-slug" layout="alto|lungo" numero="10" col="1"]
 * [mps_lista_contenuti tipo="post-type" post_type="post" categoria="nome-o-slug" layout="alto|lungo" numero="10" col="3"]
 *
 * - tipo=argomento → filtra per tag (Argomento); argomento obbligatorio
 * - tipo=post-type → filtra per CPT; categoria/tipologia facoltativa
 * - layout=alto → card stile home (orizzontale/verticale da config homepage)
 * - layout=lungo → card article degli archivi argomento/tipologia
 * - numero → elementi per "pagina" / batch del tasto Mostra altri (default 10)
 * - col → colonne flex/bootstrap (default 1)
 *
 * @package Design_Scuole_Italia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Mappa post type → tassonomia tipologia.
 *
 * @return array<string, string>
 */
function mps_lista_contenuti_tipologia_map() {
	return array(
		'post'            => 'tipologia-articolo',
		'circolare'       => 'tipologia-circolare',
		'evento'          => 'tipologia-evento',
		'documento'       => 'tipologia-documento',
		'servizio'        => 'tipologia-servizio',
		'luogo'           => 'tipologia-luogo',
		'struttura'       => 'tipologia-struttura',
		'scheda_progetto' => 'tipologia-progetto',
		'indirizzo'       => 'percorsi-di-studio',
	);
}

/**
 * Post type inclusi nelle liste per argomento (come archive tag).
 *
 * @return string[]
 */
function mps_lista_contenuti_argomento_post_types() {
	return array(
		'post',
		'circolare',
		'servizio',
		'documento',
		'luogo',
		'struttura',
		'evento',
		'scheda_progetto',
		'scheda_didattica',
		'indirizzo',
		'page',
	);
}

/**
 * Classe colonna da attributo col (numero colonne flex).
 *
 * @param int $col Numero colonne (1–6).
 * @return string
 */
function mps_lista_contenuti_col_class( $col ) {
	$col = (int) $col;
	if ( $col < 1 ) {
		$col = 1;
	}
	if ( $col > 6 ) {
		$col = 6;
	}

	// Bootstrap quando 12 è divisibile; altrimenti flex via data-cols sul wrapper.
	$map = array(
		1 => 12,
		2 => 6,
		3 => 4,
		4 => 3,
		6 => 2,
	);

	if ( isset( $map[ $col ] ) ) {
		$span    = $map[ $col ];
		$md_span = ( $col === 1 ) ? 12 : ( ( $col <= 2 ) ? 6 : 6 );
		return 'col-12 col-md-' . $md_span . ' col-lg-' . $span . ' mb-4 mps-lista-contenuti__item';
	}

	return 'col-12 col-md-6 mb-4 mps-lista-contenuti__item mps-lista-contenuti__item--flex';
}

/**
 * Risolve un termine per slug o nome.
 *
 * @param string $value    Slug o nome.
 * @param string $taxonomy Taxonomy slug.
 * @return WP_Term|null
 */
function mps_lista_contenuti_resolve_term( $value, $taxonomy ) {
	$value = trim( (string) $value );
	if ( $value === '' || ! taxonomy_exists( $taxonomy ) ) {
		return null;
	}

	$term = get_term_by( 'slug', sanitize_title( $value ), $taxonomy );
	if ( ! $term || is_wp_error( $term ) ) {
		$term = get_term_by( 'name', $value, $taxonomy );
	}
	if ( ! $term || is_wp_error( $term ) ) {
		$term = get_term_by( 'slug', $value, $taxonomy );
	}

	return ( $term && ! is_wp_error( $term ) ) ? $term : null;
}

/**
 * Normalizza attributi shortcode / AJAX.
 *
 * @param array $atts Attributi grezzi.
 * @return array|WP_Error
 */
function mps_lista_contenuti_normalize_atts( $atts ) {
	$atts = shortcode_atts(
		array(
			'tipo'      => '',
			'argomento' => '',
			'post_type' => '',
			'categoria' => '',
			'tipologia' => '',
			'layout'    => 'lungo',
			'numero'    => 10,
			'col'       => 1,
		),
		$atts,
		'mps_lista_contenuti'
	);

	$tipo   = sanitize_key( $atts['tipo'] );
	$layout = sanitize_key( $atts['layout'] );
	if ( ! in_array( $layout, array( 'alto', 'lungo' ), true ) ) {
		$layout = 'lungo';
	}

	$numero = (int) $atts['numero'];
	if ( $numero <= 0 ) {
		$numero = 10;
	}

	$col = (int) $atts['col'];
	if ( $col <= 0 ) {
		$col = 1;
	}
	if ( $col > 6 ) {
		$col = 6;
	}

	$categoria = $atts['categoria'] !== '' ? $atts['categoria'] : $atts['tipologia'];
	$categoria = trim( (string) $categoria );

	$normalized = array(
		'tipo'      => $tipo,
		'argomento' => trim( (string) $atts['argomento'] ),
		'post_type' => sanitize_key( $atts['post_type'] ),
		'categoria' => $categoria,
		'layout'    => $layout,
		'numero'    => $numero,
		'col'       => $col,
	);

	if ( $tipo === 'argomento' ) {
		if ( $normalized['argomento'] === '' ) {
			return new WP_Error( 'mps_lista_missing_argomento', 'Argomento mancante' );
		}
		$term = mps_lista_contenuti_resolve_term( $normalized['argomento'], 'post_tag' );
		if ( ! $term ) {
			return new WP_Error( 'mps_lista_invalid_argomento', 'Argomento non trovato' );
		}
		$normalized['term_id']  = (int) $term->term_id;
		$normalized['taxonomy'] = 'post_tag';
	} elseif ( $tipo === 'post-type' || $tipo === 'post_type' ) {
		$normalized['tipo'] = 'post-type';
		if ( $normalized['post_type'] === '' || ! post_type_exists( $normalized['post_type'] ) ) {
			return new WP_Error( 'mps_lista_invalid_post_type', 'Post type non valido' );
		}

		if ( $categoria !== '' ) {
			$map      = mps_lista_contenuti_tipologia_map();
			$taxonomy = isset( $map[ $normalized['post_type'] ] ) ? $map[ $normalized['post_type'] ] : '';

			if ( $taxonomy === '' ) {
				$object_taxonomies = get_object_taxonomies( $normalized['post_type'], 'names' );
				foreach ( (array) $object_taxonomies as $tax_name ) {
					if ( strpos( $tax_name, 'tipologia-' ) === 0 || $tax_name === 'percorsi-di-studio' ) {
						$taxonomy = $tax_name;
						break;
					}
				}
			}

			if ( ! $taxonomy ) {
				return new WP_Error( 'mps_lista_no_taxonomy', 'Nessuna tipologia per il post type' );
			}

			$term = mps_lista_contenuti_resolve_term( $categoria, $taxonomy );
			if ( ! $term ) {
				return new WP_Error( 'mps_lista_invalid_categoria', 'Categoria non trovata' );
			}

			$normalized['term_id']  = (int) $term->term_id;
			$normalized['taxonomy'] = $taxonomy;
		}
	} else {
		return new WP_Error( 'mps_lista_invalid_tipo', 'Tipo non valido' );
	}

	return $normalized;
}

/**
 * Costruisce gli argomenti WP_Query.
 *
 * @param array $normalized Attributi normalizzati.
 * @param int   $page       Pagina (1-based).
 * @return array
 */
function mps_lista_contenuti_build_query_args( $normalized, $page = 1 ) {
	$page = max( 1, (int) $page );

	$query_args = array(
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'posts_per_page'      => (int) $normalized['numero'],
		'paged'               => $page,
		'orderby'             => 'date',
		'order'               => 'DESC',
	);

	if ( $normalized['tipo'] === 'argomento' ) {
		$query_args['post_type'] = mps_lista_contenuti_argomento_post_types();
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'post_tag',
				'field'    => 'term_id',
				'terms'    => (int) $normalized['term_id'],
			),
		);
	} else {
		$query_args['post_type'] = $normalized['post_type'];
		if ( ! empty( $normalized['term_id'] ) && ! empty( $normalized['taxonomy'] ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => $normalized['taxonomy'],
					'field'    => 'term_id',
					'terms'    => (int) $normalized['term_id'],
				),
			);
		}
	}

	return $query_args;
}

/**
 * Render di una singola card in base al layout.
 *
 * @param WP_Post $post   Post corrente (già in global).
 * @param string  $layout alto|lungo
 */
function mps_lista_contenuti_render_card( $post, $layout ) {
	global $set_card_wrapper;

	if ( $layout === 'lungo' ) {
		get_template_part( 'template-parts/list/article', $post->post_type );
		return;
	}

	$set_card_wrapper = true;
	$content_type     = 'post';
	if ( $post->post_type === 'evento' ) {
		$content_type = 'evento';
	} elseif ( $post->post_type === 'circolare' ) {
		$content_type = 'circolare';
	}

	if ( function_exists( 'mps_get_template_part_home_card' ) && in_array( $post->post_type, array( 'post', 'circolare', 'evento' ), true ) ) {
		mps_get_template_part_home_card( $content_type, 'fascia_verticale' );
		return;
	}

	if ( function_exists( 'mps_home_use_card_orizzontali' ) && mps_home_use_card_orizzontali() ) {
		get_template_part( 'template-parts/single/card', 'home-preview' );
		return;
	}

	get_template_part( 'template-parts/single/card-vertical-thumb', $post->post_type );
}

/**
 * Render HTML degli item (solo colonne/card, senza wrapper esterno).
 *
 * @param WP_Query $query       Query.
 * @param string   $layout      alto|lungo.
 * @param string   $col_class   Classe colonna.
 * @return string
 */
function mps_lista_contenuti_render_items( $query, $layout, $col_class ) {
	global $post, $set_card_wrapper;

	ob_start();

	while ( $query->have_posts() ) {
		$query->the_post();
		echo '<div class="' . esc_attr( $col_class ) . '">';
		mps_lista_contenuti_render_card( $post, $layout );
		echo '</div>';
	}

	$set_card_wrapper = false;

	return ob_get_clean();
}

/**
 * Enqueue script load-more.
 */
function mps_lista_contenuti_enqueue_assets() {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done = true;

	$handle = 'mps-lista-contenuti';
	$src    = get_stylesheet_directory_uri() . '/assets/js/lista-contenuti.js';
	$path   = get_stylesheet_directory() . '/assets/js/lista-contenuti.js';
	$ver    = file_exists( $path ) ? (string) filemtime( $path ) : '1.0.0';

	wp_enqueue_script( $handle, $src, array( 'jquery' ), $ver, true );
	wp_localize_script(
		$handle,
		'mpsListaContenuti',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		)
	);
}

/**
 * Callback shortcode [mps_lista_contenuti].
 *
 * @param array|string $atts Attributi.
 * @return string
 */
function mps_lista_contenuti_shortcode( $atts ) {
	$normalized = mps_lista_contenuti_normalize_atts( is_array( $atts ) ? $atts : array() );
	if ( is_wp_error( $normalized ) ) {
		return '';
	}

	$query = new WP_Query( mps_lista_contenuti_build_query_args( $normalized, 1 ) );
	if ( ! $query->have_posts() ) {
		wp_reset_postdata();
		return '';
	}

	mps_lista_contenuti_enqueue_assets();

	$col_class = mps_lista_contenuti_col_class( $normalized['col'] );
	$has_more  = (int) $query->max_num_pages > 1;
	$nonce     = wp_create_nonce( 'mps_lista_contenuti' );

	ob_start();
	?>
	<div class="mps-lista-contenuti mps-lista-contenuti--<?php echo esc_attr( $normalized['layout'] ); ?>" data-cols="<?php echo esc_attr( (string) $normalized['col'] ); ?>">
		<div class="row variable-gutters mps-lista-contenuti__list">
			<?php echo mps_lista_contenuti_render_items( $query, $normalized['layout'], $col_class ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php if ( $has_more ) { ?>
			<div class="mps-lista-contenuti__actions text-center mt-4">
				<button
					type="button"
					class="btn btn-outline-primary mps-lista-contenuti__load-more"
					data-page="1"
					data-numero="<?php echo esc_attr( (string) $normalized['numero'] ); ?>"
					data-layout="<?php echo esc_attr( $normalized['layout'] ); ?>"
					data-col="<?php echo esc_attr( (string) $normalized['col'] ); ?>"
					data-tipo="<?php echo esc_attr( $normalized['tipo'] ); ?>"
					data-argomento="<?php echo esc_attr( $normalized['argomento'] ); ?>"
					data-post-type="<?php echo esc_attr( $normalized['post_type'] ); ?>"
					data-categoria="<?php echo esc_attr( $normalized['categoria'] ); ?>"
					data-nonce="<?php echo esc_attr( $nonce ); ?>"
				>
					<?php esc_html_e( 'Mostra altri', 'design_scuole_italia' ); ?>
				</button>
			</div>
		<?php } ?>
	</div>
	<?php

	wp_reset_postdata();

	return ob_get_clean();
}

/**
 * AJAX: carica il batch successivo.
 */
function mps_lista_contenuti_ajax_load_more() {
	check_ajax_referer( 'mps_lista_contenuti', 'nonce' );

	$page = isset( $_POST['page'] ) ? (int) $_POST['page'] : 1;
	if ( $page < 2 ) {
		wp_send_json_error( array( 'message' => 'Pagina non valida' ), 400 );
	}

	$atts = array(
		'tipo'      => isset( $_POST['tipo'] ) ? wp_unslash( $_POST['tipo'] ) : '',
		'argomento' => isset( $_POST['argomento'] ) ? wp_unslash( $_POST['argomento'] ) : '',
		'post_type' => isset( $_POST['post_type'] ) ? wp_unslash( $_POST['post_type'] ) : '',
		'categoria' => isset( $_POST['categoria'] ) ? wp_unslash( $_POST['categoria'] ) : '',
		'layout'    => isset( $_POST['layout'] ) ? wp_unslash( $_POST['layout'] ) : 'lungo',
		'numero'    => isset( $_POST['numero'] ) ? wp_unslash( $_POST['numero'] ) : 10,
		'col'       => isset( $_POST['col'] ) ? wp_unslash( $_POST['col'] ) : 1,
	);

	$normalized = mps_lista_contenuti_normalize_atts( $atts );
	if ( is_wp_error( $normalized ) ) {
		wp_send_json_error( array( 'message' => $normalized->get_error_message() ), 400 );
	}

	$query = new WP_Query( mps_lista_contenuti_build_query_args( $normalized, $page ) );
	if ( ! $query->have_posts() ) {
		wp_reset_postdata();
		wp_send_json_success(
			array(
				'html'     => '',
				'has_more' => false,
			)
		);
	}

	$col_class = mps_lista_contenuti_col_class( $normalized['col'] );
	$html      = mps_lista_contenuti_render_items( $query, $normalized['layout'], $col_class );
	$has_more  = $page < (int) $query->max_num_pages;

	wp_reset_postdata();

	wp_send_json_success(
		array(
			'html'     => $html,
			'has_more' => $has_more,
		)
	);
}

add_shortcode( 'mps_lista_contenuti', 'mps_lista_contenuti_shortcode' );
add_action( 'wp_ajax_mps_lista_contenuti_load_more', 'mps_lista_contenuti_ajax_load_more' );
add_action( 'wp_ajax_nopriv_mps_lista_contenuti_load_more', 'mps_lista_contenuti_ajax_load_more' );
