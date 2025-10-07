<?php
/* Template Name: Home_completa
 *
 * notizie template file
 *
 * @package Design_Scuole_Italia
 */
global $post, $tipologia_notizia, $ct;
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
            
			get_template_part("template-parts/home/banner");

			$home_is_selezione_automatica = dsi_get_option("home_is_selezione_automatica", "homepage");
            
            get_template_part("template-parts/home/contenuti-in-evidenza");

            if($home_is_selezione_automatica == "true_horizontal") {
                get_template_part("template-parts/home/novita", "orizzontale");
            }else if($home_is_selezione_automatica != "false") {
                get_template_part("template-parts/home/novita", "verticale");
            } 

         
///////////////////////////////////////////////////////////////////////	// inizio loop.	
			
while ( have_posts() ) :
			the_post();

	//		get_template_part("template-parts/hero/notizie");
			$tipologie_notizie = dsi_get_option("tipologie_notizie", "notizie");
			$ct=1;
			if(is_array($tipologie_notizie) && count($tipologie_notizie)){
				foreach ( $tipologie_notizie as $id_tipologia_notizia ) {
					$tipologia_notizia = get_term_by("id", $id_tipologia_notizia, "tipologia-articolo");
					get_template_part("template-parts/home/notizie", "tipologie");
					$ct++;
				}

			}
	
            get_template_part("template-parts/home/notizie", "circolari");
            $ct++;
            get_template_part("template-parts/home/eventi");
		?>
			<?php get_template_part('dynamic_sidebar') || !dynamic_sidebar('Home Centrale News');?>
			
        <section class="section bg-white">
        <?php get_template_part("template-parts/hero/servizi"); ?>
        <?php get_template_part("template-parts/home/list", "servizi"); ?>
		<?php get_template_part('dynamic_sidebar') || !dynamic_sidebar('Home Centrale Servizi');?>
        </section>
		
            <?php
		endwhile; // End of the loop.
///////////////////////////////////////////////////////////////////////		
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
