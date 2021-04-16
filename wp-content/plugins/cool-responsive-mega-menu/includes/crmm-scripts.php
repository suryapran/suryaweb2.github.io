<?php
// Register Script
function cool_megamenu_scripts() {

	//wp_register_style( 'bootstrap', CRMM_URL . 'assets/bootstrap-3/bootstrap.min.css', false, '4.1.3' );
	wp_register_style( 'bootstrap', CRMM_URL . 'assets/bootstrap-4/css/bootstrap.min.css', false, '4.1.3' );
	// Search for template in stylesheet directory
    if ( file_exists( get_stylesheet_directory() . '/cool-megamenu/css/crmm.css' ) )
        $cssfile = get_stylesheet_directory_uri() . '/cool-megamenu/css/crmm.css';
    // Search for template in theme directory
    elseif ( file_exists( get_template_directory() . '/cool-megamenu/css/crmm.css' ) )
        $cssfile = get_template_directory_uri() . '/cool-megamenu/css/crmm.css';
    // Template not found
    else
        $cssfile = CRMM_URL . 'assets/css/crmm.css';
    
	wp_register_style( 'cool-megamenu', esc_url($cssfile), array('bootstrap'), CRMM_VERSION );
	wp_enqueue_style( 'cool-megamenu' );

	wp_register_script( 'popper', CRMM_URL . 'assets/bootstrap-4/js/popper.min.js', array( 'jquery' ), '1.12.2', true );	 
	wp_register_script( 'bootstrap', CRMM_URL . 'assets/bootstrap-4/js/bootstrap.min.js', array( 'jquery', 'popper' ), '4.1.3', true );	 
	// Search for template in stylesheet directory
    if ( file_exists( get_stylesheet_directory() . '/cool-megamenu/js/crmm.js' ) )
        $file = get_stylesheet_directory_uri() . '/cool-megamenu/js/crmm.js';
    // Search for template in theme directory
    elseif ( file_exists( get_template_directory() . '/cool-megamenu/js/crmm.js' ) )
        $file = get_template_directory_uri() . '/cool-megamenu/js/crmm.js';
    // Template not found
    else
        $file = CRMM_URL . 'assets/js/crmm.js';

	wp_enqueue_script( 'cool-megamenu', esc_url($file), array( 'bootstrap' ), CRMM_VERSION, true );	 

	$arr = array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' )
		);
	wp_localize_script( 'cool-megamenu-localize', 'CRMM', $arr );

}
add_action( 'wp_enqueue_scripts', 'cool_megamenu_scripts' );
