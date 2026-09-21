<?php
/* Template Name: HomeStd hero + carosello notizie
 *
 * Come HomeStd con carosello notizie, ma hero scuola sempre visibile
 * e carosello subito sotto (solo se ci sono articoli manuali).
 *
 * @package Design_Scuole_Italia
 */
get_header();
?>
    <main id="main-container" class="main-container redbrown">
        <?php
        if ( have_posts() ) :
            $messages = dsi_get_option( "messages", "home_messages" );
            if($messages && !empty($messages)) {
                get_template_part("template-parts/home/messages");
            }

            get_template_part("template-parts/hero/home");
            get_template_part("template-parts/hero/slider-notizie", "sotto-hero");

            get_template_part("template-parts/home/banner");

           $home_is_selezione_automatica = dsi_get_option("home_is_selezione_automatica", "homepage");
            
            if($home_is_selezione_automatica == "true_horizontal") {
                get_template_part("template-parts/home/novita", "orizzontale");
            }else if($home_is_selezione_automatica != "false") {
                get_template_part("template-parts/home/novita", "verticale");
            }
		?>
			<?php get_template_part('dynamic_sidebar') || !dynamic_sidebar('Home Centrale News');?>
        <section class="section bg-white">
        <?php get_template_part("template-parts/hero/servizi"); ?>
        <?php get_template_part("template-parts/home/list", "servizi"); ?>
		<?php get_template_part('dynamic_sidebar') || !dynamic_sidebar('Home Centrale Servizi');?>
        </section>
            <?php
            $visualizzazione_didattica = dsi_get_option("visualizzazione_didattica", "didattica");
            if($visualizzazione_didattica == "scuole")
                get_template_part("template-parts/home/didattica", "cicli");
            else if($visualizzazione_didattica == "indirizzi")
                get_template_part("template-parts/home/didattica", "cicli-indirizzi");

              get_template_part("template-parts/home/didattica", "risorse");

        endif; // End of the loop.
		
		
		$home_argomenti = dsi_get_option("home_argomenti", "homepage");
		
		if (is_array($home_argomenti) && count($home_argomenti)) {
			?>
				<section class="section bg-white">
					<?php get_template_part("template-parts/hero/argomenti"); ?>
					<?php get_template_part("template-parts/home/list", "argomenti"); ?>
				</section>
			<?php
		}
        ?>
		<?php get_template_part('dynamic_sidebar') || !dynamic_sidebar('Home Centrale Didattica');?>
    </main>
<?php
get_footer();
