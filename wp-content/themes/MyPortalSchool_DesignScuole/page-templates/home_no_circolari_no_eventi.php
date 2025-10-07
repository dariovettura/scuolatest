<?php
/* Template Name: Home_no_circolari_no_eventi
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
			  

//            $home_is_selezione_automatica = dsi_get_option("home_is_selezione_automatica", "homepage");
//          if($home_is_selezione_automatica == "false"){
//               get_template_part("template-parts/home/articoli", "manuali");
//          }else{
//                get_template_part("template-parts/home/articoli", "eventi");
//          }   


         

	// get_template_part("template-parts/luogo/map");
		
		
///////////////////////////////////////////////////////////////////////	// inizio of the loop.			
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
		get_template_part('dynamic_sidebar') || !dynamic_sidebar('Home Centrale News');?>
			
        <section class="section bg-white">
        <?php get_template_part("template-parts/hero/servizi"); ?>
        <?php get_template_part("template-parts/home/list", "servizi"); ?>
		<?php get_template_part('dynamic_sidebar') || !dynamic_sidebar('Home Centrale Servizi');?>
        </section>
		
            <?php
		endwhile; // End of the loopolo.
///////////////////////////////////////////////////////////////////////		
		   $visualizzazione_didattica = dsi_get_option("visualizzazione_didattica", "didattica");
            if($visualizzazione_didattica == "scuole")
                get_template_part("template-parts/home/didattica", "cicli");
            else if($visualizzazione_didattica == "indirizzi")
                get_template_part("template-parts/home/didattica", "cicli-indirizzi");

              get_template_part("template-parts/home/didattica", "risorse");

			endif; // End of the loop.
        ?>
		<?php get_template_part('dynamic_sidebar') || !dynamic_sidebar('Home Centrale Didattica');?>
    </main>
<?php
get_footer();
