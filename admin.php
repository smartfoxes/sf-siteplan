<?php

add_action( 'admin_enqueue_scripts', 'sf_siteplan_register_scripts' );


function sf_siteplan_register_scripts() { 
    wp_register_script ( 'sf_siteplan_admin_js', plugin_dir_url( __FILE__ ) . 'js/admin.js' , false, '1.0.0', true); 
    
    wp_enqueue_media();
    wp_enqueue_script('media-upload');
    wp_enqueue_script( 'sf_siteplan_admin_js' );      
    
}
