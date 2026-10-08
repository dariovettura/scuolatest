<?php
/**
 * Sezione In evidenza — override child con card preview se attiva l'opzione.
 *
 * @package Design_Scuole_Italia
 */
global $set_card_wrapper, $documento, $post;

$home_articoli_manuali = dsi_get_option( 'home_articoli_manuali', 'homepage' );

if ( ! is_array( $home_articoli_manuali ) || ! count( $home_articoli_manuali ) ) {
	return;
}
?>
<section class="section bg-white py-2 py-lg-3 py-xl-5">
	<div class="container">
		<div class="title-section pb-4">
			<h2 class="h2"><?php _e( 'In evidenza', 'design_scuole_italia' ); ?></h2>
		</div><!-- /title-section -->
		<div class="row variable-gutters">
			<?php
			foreach ( $home_articoli_manuali as $idpost ) {
				$post = get_post( $idpost );
				$set_card_wrapper = true;
				if ( ! $post ) {
					continue;
				}
				$content_type = 'post';
				if ( $post->post_type === 'evento' ) {
					$content_type = 'evento';
				} elseif ( $post->post_type === 'circolare' ) {
					$content_type = 'circolare';
				}
				?>
				<div class="col-lg-4 mb-4">
					<?php
					if ( function_exists( 'mps_get_template_part_home_card' ) ) {
						mps_get_template_part_home_card( $content_type, 'fascia_verticale' );
					} elseif ( $post->post_type === 'evento' ) {
						get_template_part( 'template-parts/evento/card' );
					} else {
						get_template_part( 'template-parts/single/card-vertical-thumb', $post->post_type );
					}
					?>
				</div><!-- /col-lg-4 -->
				<?php
			}
			wp_reset_postdata();
			?>
		</div><!-- /row -->
	</div><!-- /container -->
</section><!-- /section -->
