<?php
/**
 * Box correlati — override child: card preview se attiva l'opzione orizzontale.
 *
 * @package Design_Scuole_Italia
 */
global $post, $related_type, $posts_array;

if ( ! $related_type ) {
	$related_type = 'card-vertical-thumb';
}
$oldpost = $post;

if ( ! is_array( $posts_array ) || ! count( $posts_array ) ) {
	return;
}
?>
<section class="section bg-gray-gradient py-5" id="art-par-correlati">
	<div class="container pt-3">
		<div class="row variable-gutters">
			<div class="col-lg-12">
				<h2 class="h3 mb-5 text-center semi-bold text-gray-primary"><?php _e( 'Circolari, notizie, eventi correlati', 'design_scuole_italia' ); ?></h2>
				<div class="it-carousel-wrapper carousel-notice it-carousel-landscape-abstract-three-cols splide" data-bs-carousel-splide>
					<div class="splide__track ps-lg-3 pe-lg-3">
						<ul class="splide__list it-carousel-all">
							<?php
							foreach ( $posts_array as $post ) {
								$content_type = 'post';
								if ( $post->post_type === 'evento' ) {
									$content_type = 'evento';
								} elseif ( $post->post_type === 'circolare' ) {
									$content_type = 'circolare';
								}
								?>
								<li class="splide__slide">
									<?php
									if ( function_exists( 'mps_get_template_part_home_card' ) ) {
										mps_get_template_part_home_card( $content_type, 'impilata' );
									} else {
										get_template_part( 'template-parts/single/' . $related_type, $post->post_type );
									}
									?>
								</li><!-- /item -->
							<?php } ?>
						</ul>
					</div><!-- /carousel-large -->
				</div>
			</div><!-- /col-lg-12 -->
		</div><!-- /row -->
	</div><!-- /container -->
</section>
<?php
$post = $oldpost;
