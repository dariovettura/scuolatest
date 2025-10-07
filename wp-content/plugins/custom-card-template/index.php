<?php
/*
Plugin Name: Custom Card Template
Description: Sovrascrive il template card-vertical-thumb.php e aggiunge un CSS personalizzato.
Version: 1.0
Author: Dario
*/

// Funzione per caricare il CSS solo se il plugin è attivo
function custom_card_enqueue_styles() {
    wp_enqueue_style('custom-card-style', plugin_dir_url(__FILE__) . 'custom-card-style.css');
}
add_action('wp_enqueue_scripts', 'custom_card_enqueue_styles');

// Funzione per sovrascrivere il template card-vertical-thumb.php
function custom_card_template_override($template) {
    
        // Percorso del template da sovrascrivere
        $theme_template_path = get_stylesheet_directory() . '/template-parts/single/card-vertical-thumb.php';
        
        // Se il template corrente è quello che vogliamo sovrascrivere
        if ($template === $theme_template_path) {
            $custom_template = plugin_dir_path(__FILE__) . 'card-vertical-thumb.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
    }
    return $template;

add_filter('template_include', 'custom_card_template_override');

