<?php
global $post, $ct;

$container_class = 'bg-white';
if ( $ct % 2 ) {
	$container_class = 'bg-gray-light';
}
?>

<section class="section <?php echo esc_attr( $container_class ); ?> py-5">
	<div class="container">
		<div class="title-section mb-5">
			<h2 class="h4"><?php esc_html_e( 'Eventi', 'design_scuole_italia' ); ?></h2>
		</div><!-- /title-large -->
		<div class="it-carousel-wrapper carousel-notice it-carousel-landscape-abstract-three-cols splide" data-bs-carousel-splide>
			<div class="splide__track">
				<ul class="splide__list">
					<?php
					$args  = array(
						'post_type'      => 'evento',
						'posts_per_page' => 9,
					);
					$posts = get_posts( $args );
					foreach ( $posts as $post ) {
						?>
					<li class="splide__slide">
						<div class="it-single-slide-wrapper h-100">
							<?php mps_get_template_part_home_card( 'evento', 'impilata' ); ?>
						</div>
					</li>
						<?php
					}
					?>
				</ul>
			</div>
		</div><!-- /carousel-large -->
		<div class="pt-3 text-center">
			<a class="text-underline" href="<?php echo esc_url( get_post_type_archive_link( 'evento' ) ); ?>"><strong><?php esc_html_e( 'Vedi tutti', 'design_scuole_italia' ); ?></strong></a>
		</div>
	</div><!-- /container -->
</section><!-- /section -->
