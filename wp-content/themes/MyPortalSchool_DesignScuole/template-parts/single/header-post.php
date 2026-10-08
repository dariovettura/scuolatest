<?php
/**
 * Header single post — layout flex (niente background absolute).
 *
 * @package Design_Scuole_Italia
 */
global $post, $autore, $luogo, $c, $badgeclass;

$link_schede_documenti = dsi_get_meta( 'link_schede_documenti' );
$file_documenti        = dsi_get_meta( 'file_documenti' );
$luoghi                = dsi_get_meta( 'luoghi' );
$persone               = dsi_get_meta( 'persone' );
$numerazione_circolare = dsi_get_meta( 'numerazione_circolare' );

$has_thumb = has_post_thumbnail( $post );
$image_id  = $has_thumb ? get_post_thumbnail_id( $post ) : 0;
$image_url = $has_thumb ? get_the_post_thumbnail_url( $post, 'full' ) : '';
$autore    = get_user_by( 'ID', $post->post_author );

$section_class = 'section bg-white article-title article-title-author article-title-flex';
if ( ! $has_thumb ) {
	$section_class .= ' article-title-small article-title-flex--no-media';
}
?>
<section class="<?php echo esc_attr( $section_class ); ?>">
	<div class="container">
		<div class="row variable-gutters article-title-flex__row align-items-start">
			<?php if ( $has_thumb && $image_url ) { ?>
				<div class="col-md-6 order-1 order-md-2 article-title-flex__media-col">
					<figure class="article-title-flex__media">
						<?php
						if ( function_exists( 'dsi_get_img_from_id_url' ) ) {
							dsi_get_img_from_id_url( $image_id, $image_url );
						} else {
							echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( get_the_title( $post ) ) . '">';
						}
						?>
					</figure>
				</div>
			<?php } ?>

			<div class="col-md-<?php echo $has_thumb ? '6' : '12'; ?> order-2 order-md-1 article-title-author-container">
				<div class="title-content">
					<h1><?php the_title(); ?></h1>
					<p class="mb-0"><?php echo esc_html( dsi_get_meta( 'descrizione' ) ); ?></p>
				</div><!-- /title-content -->
				<div class="card card-avatar card-comments">
					<div class="card-body p-0">
						<?php get_template_part( 'template-parts/autore/card' ); ?>
						<?php if ( dsi_get_option( 'show_contatore_commenti', 'setup' ) != 'false' ) { ?>
							<div class="comments ml-auto">
								<p><?php echo (int) $post->comment_count; ?></p>
							</div><!-- /comments -->
						<?php } ?>
					</div><!-- /card-body -->
				</div><!-- /card card-avatar -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</section>
