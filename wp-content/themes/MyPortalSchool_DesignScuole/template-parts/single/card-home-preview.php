<?php
/**
 * Card preview home: immagine in alto (o placeholder), data, titolo, descrizione, argomenti.
 * - post: footer autore
 * - evento: footer date (come card evento classica)
 * - circolare: numerazione in evidenza
 *
 * @package Design_Scuole_Italia
 */
global $post, $autore, $set_card_top_margin, $set_card_wrapper;

$autore = get_user_by( 'ID', $post->post_author );

$image_id  = get_post_thumbnail_id( $post );
$image_url = get_the_post_thumbnail_url( $post, 'large' );
$show_contatore_commenti = dsi_get_option( 'show_contatore_commenti', 'setup' );

$color_class = function_exists( 'dsi_get_post_types_color_class' ) ? dsi_get_post_types_color_class( $post->post_type ) : 'greendark';
$icon_class  = function_exists( 'dsi_get_post_types_icon_class' ) ? dsi_get_post_types_icon_class( $post->post_type ) : 'newspaper';

$descrizione = '';
$numerazione_circolare = '';
$in_corso = false;
$date_ts = strtotime( $post->post_date );

if ( $post->post_type === 'post' ) {
	$descrizione = $post->_dsi_articolo_descrizione;
} elseif ( $post->post_type === 'circolare' ) {
	$descrizione = $post->_dsi_circolare_descrizione;
	$numerazione_circolare = dsi_get_meta( 'numerazione_circolare', '', $post->ID );
	if ( ! $numerazione_circolare ) {
		$numerazione_circolare = dsi_get_meta( 'numerazione_circolare' );
	}
} elseif ( $post->post_type === 'evento' ) {
	$descrizione = dsi_get_meta( 'descrizione', '', $post->ID );

	$timestamp_inizio = dsi_get_meta( 'timestamp_inizio', '_dsi_evento_', $post->ID );
	$timestamp_fine   = dsi_get_meta( 'timestamp_fine', '_dsi_evento_', $post->ID );
	$dataora_inizio   = date_i18n( 'Y-m-d H:i', $timestamp_inizio );
	$dataora_fine     = date_i18n( 'Y-m-d H:i', $timestamp_fine );
	$dataora_adesso   = date_i18n( 'Y-m-d H:i', time() );

	if ( $timestamp_inizio ) {
		$date_ts = (int) $timestamp_inizio;
	}

	if ( $dataora_inizio <= $dataora_adesso && $dataora_adesso <= $dataora_fine ) {
		$in_corso = true;
	}
}

if ( ! $descrizione ) {
	$descrizione = get_the_excerpt( $post );
}

$argomenti = function_exists( 'dsi_get_argomenti_of_post' ) ? dsi_get_argomenti_of_post( $post ) : array();

$accesso_circolare = 'true';
if ( $post->post_type === 'circolare' && function_exists( 'circolare_access' ) ) {
	$accesso_circolare = circolare_access( $post->ID );
}

$classes = array( 'card', 'card-bg', 'card-home-preview', 'bg-white', 'card-thumb-rounded' );
if ( $post->post_type === 'evento' ) {
	$classes[] = 'card-event';
	if ( $in_corso ) {
		$classes[] = 'border';
		$classes[] = 'border-success';
	}
}
if ( ! empty( $set_card_wrapper ) ) {
	$classes[] = 'card-wrapper';
}
if ( ! empty( $set_card_top_margin ) ) {
	$classes[] = 'mt-2';
}

$show_media = ( $accesso_circolare != 'false' );
?>
<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<?php if ( $show_media ) { ?>
		<a class="card-home-preview__media<?php echo $image_url ? '' : ' card-home-preview__media--placeholder'; ?>"
			href="<?php echo esc_url( get_permalink( $post ) ); ?>"
			aria-hidden="true"
			tabindex="-1">
			<div class="date card-home-preview__date card-home-preview__date--<?php echo esc_attr( $color_class ); ?>">
				<span class="year"><?php echo esc_html( date_i18n( 'Y', $date_ts ) ); ?></span>
				<span class="day"><?php echo esc_html( date_i18n( 'd', $date_ts ) ); ?></span>
				<span class="month"><?php echo esc_html( date_i18n( 'M', $date_ts ) ); ?></span>
			</div>
			<?php if ( $image_url ) { ?>
				<?php dsi_get_img_from_id_url( $image_id, $image_url ); ?>
			<?php } else { ?>
				<svg class="icon-<?php echo esc_attr( $color_class ); ?> svg-<?php echo esc_attr( $icon_class ); ?>" aria-hidden="true">
					<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-<?php echo esc_attr( $icon_class ); ?>"></use>
				</svg>
			<?php } ?>
		</a>
	<?php } ?>

	<div class="card-home-preview__body">
		<?php if ( $post->post_type === 'circolare' && $accesso_circolare == 'false' ) { ?>
			<p class="font-weight-bold mb-0">
				<?php
				printf(
					/* translators: %s: numerazione circolare */
					esc_html__( 'Il contenuto della circolare numero %s è riservato.', 'design_scuole_italia' ),
					esc_html( $numerazione_circolare )
				);
				?>
			</p>
		<?php } else { ?>
			<?php if ( $post->post_type === 'circolare' && $numerazione_circolare !== '' && $numerazione_circolare !== false ) { ?>
				<small class="card-home-preview__meta h6 text-greendark">
					<?php esc_html_e( 'Circolare ', 'design_scuole_italia' ); echo esc_html( $numerazione_circolare ); ?>
				</small>
			<?php } ?>

			<h3 class="card-home-preview__title h5">
				<a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a>
			</h3>

			<?php if ( $descrizione ) { ?>
				<p class="card-home-preview__excerpt"><?php echo esc_html( wp_strip_all_tags( $descrizione ) ); ?></p>
			<?php } ?>

			<?php if ( is_array( $argomenti ) && count( $argomenti ) ) { ?>
				<div class="card-home-preview__tags badges">
					<?php foreach ( $argomenti as $item ) { ?>
						<a href="<?php echo esc_url( get_term_link( $item ) ); ?>"
							class="badge badge-sm badge-pill badge-outline-greendark"
							title="<?php echo esc_attr( sprintf( __( "Vai all'argomento: %s", 'design_scuole_italia' ), $item->name ) ); ?>">
							<?php echo esc_html( $item->name ); ?>
						</a>
					<?php } ?>
				</div>
			<?php } ?>
		<?php } ?>
	</div>

	<?php if ( $post->post_type === 'evento' ) { ?>
		<div class="card-event-dates card-home-preview__footer card-home-preview__footer--event">
			<div class="card-event-dates-icon">
				<svg class="icon svg-calendar"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-calendar"></use></svg>
			</div><!-- /card-event-dates-icon -->
			<div class="card-event-dates-content">
				<?php if ( $in_corso ) { ?>
					<p class="font-weight-bold text-greendark"><?php esc_html_e( 'In svolgimento', 'design_scuole_italia' ); ?></p>
				<?php } ?>
				<p class="font-weight-normal"><?php echo esc_html( dsi_get_date_evento( $post ) ); ?></p>
			</div><!-- /card-event-dates-content -->
		</div><!-- /card-event-dates -->
	<?php } elseif ( $post->post_type === 'post' ) { ?>
		<div class="card-comments-wrapper card-home-preview__footer">
			<?php get_template_part( 'template-parts/autore/card' ); ?>
			<?php if ( $show_contatore_commenti != 'false' ) { ?>
				<div class="comments">
					<p><?php echo (int) $post->comment_count; ?></p>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</div>
