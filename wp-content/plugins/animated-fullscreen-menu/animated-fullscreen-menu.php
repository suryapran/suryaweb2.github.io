<?php

/**
 * Plugin Name: FullScreen Menu - Mobile Friendly and Responsive
 * Plugin URI: animated-fullscreen-menu
 * Description: Fullscreen Menu for your Website. Custom Design and Animation.
 * Author: Samuel Silva
 * Version: 2.2.0
 * Author URI: https://samuelsilva.pt
 * Text Domain: animated-fullscreen-menu
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) || ! function_exists( 'add_action' ) ) {
	exit;
}

function animatedfsmenu_load_textdomain() {
	load_plugin_textdomain( 'animated-fullscreen-menu', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}


function animatedfsmenu_get_plugin_version() {
	$plugin_data = get_plugin_data( __FILE__ );
	return $plugin_version = $plugin_data['Version'];
}

add_action( 'plugins_loaded', 'animatedfsmenu_load_textdomain' );

if ( ! function_exists( 'animatedfsm' ) ) {
    // Create a helper function for easy SDK access.
    function animatedfsm() {
        global $animatedfsm;

        if ( ! isset( $animatedfsm ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';
            $pro = false;
            
            $animatedfsm = fs_dynamic_init( array(
                'id'                  => '3887',
                'slug'                => 'animated-fullscreen-menu',
                'premium_slug'        => 'animated-fullscreen-menu',
                'type'                => 'plugin',
                'public_key'          => 'pk_95d707fced75c19ff9b793853ac8a',
                'is_premium'          => $pro,
                'premium_suffix'      => ( $pro ? 'Pro' : 'Free' ),
                // If your plugin is a serviceware, set this option to false.
                'has_premium_version' => $pro,
                'has_addons'          => false,
                'has_paid_plans'      => true,
                'trial'               => array(
                    'days'               => 14,
                    'is_require_payment' => true,
                ),
                'menu'                => array(
                    'slug'           => 'animatedfsm_settings',
                    'first-path'     => 'admin.php?page=animatedfsm_settings',
                    'contact'        => false,
                    'support'        => false,
                ),

            ) );
        }

        return $animatedfsm;
    }

    // Init Freemius.
    animatedfsm();
    // Signal that SDK was initiated.
    do_action( 'animatedfsm_loaded' );
}

class AnimatedfsMenu {

	//register plugin
	function register() {
		require_once dirname( __FILE__ ) . '/cmb.php';
		
		add_action( 'init', array( $this, 'register_menu' ) );

	}
	
	function activate() {
        
    }
    
    function register_menu() {
        register_nav_menu( 'animated-fullscreen-menu', __( 'Fullscreen Menu', 'animated-fullscreen-menu' ) );
    }
}


if ( class_exists( 'AnimatedfsMenu' ) ) {
	$animated_fs_menu = new AnimatedfsMenu();
	$animated_fs_menu->register();
}


register_activation_hook( __FILE__, array( $animated_fs_menu, 'activate' ) );


if ( isset( get_option( 'animatedfsm_settings' )['animatedfsm_on'] ) && 'on' === get_option( 'animatedfsm_settings' )['animatedfsm_on'] ) {
	require_once dirname( __FILE__ ) . '/frontend-animatedfsmenu.php';
}