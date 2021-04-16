<?php
/**
 * Initialize the custom Theme Options.
 */
add_action( 'init', 'cool_megamenu_options' );

/**
 * Build the custom settings & update OptionTree.
 *
 * @return    void
 * @since     2.0
 */
function cool_megamenu_options() {

  /* OptionTree is not loaded yet, or this is not an admin request */
  if ( ! function_exists( 'crmm_settings_id' ) || ! is_admin() )
    return false;

  /**
   * Get a copy of the saved settings array. 
   */
  $saved_settings = get_option( crmm_settings_id(), array() );
  
  /**
   * Custom settings array that will eventually be 
   * passes to the OptionTree Settings API Class.
   */
  $cool_megamenu_settings = array( 
    'contextual_help' => array( 
      'content'       => array( 
        array(
          'id'        => 'cool_megamenu_settings',
          'title'     => __( 'General settings', 'cool-megamenu' ),          
        )
      ),
      'sidebar' => '',
    ),
    'sections'        => array( 
      array(
        'id'          => 'crmm_general_settings',
        'title'       => __( 'General settings', 'cool-megamenu' )
      ),
      array(
        'id'          => 'crmm_responsive_settings',
        'title'       => __( 'Responsive settings', 'cool-megamenu' )
      )
    ),
    'settings'        => array( 
      array(
            'id'          => 'crmm_megamenu_type',
            'label'       => __( 'Megamenu type', 'cool-megamenu' ),
            'std'         => apply_filters('cool_megamenu_type', 'bs4'),
            'type'        => 'select',
            'section'     => 'crmm_general_settings',
            'condition'   => '',
            'operator'    => 'and',
            'choices'   => array(
                array(
                    'label' => 'None',
                    'value' => 'none' 
                    ),
                array(
                    'label' => 'Bootstrap 3',
                    'value' => 'bs3' 
                    ),
                array(
                    'label' => 'Bootstrap 4',
                    'value' => 'bs4' 
                    ),
                array(
                    'label' => 'Custom',
                    'value' => 'custom' 
                    ),
                )
        ),
        array(
            'id'          => 'crmm_megamenu_load_css',
            'label'       => __( 'Megamenu CSS load', 'cool-megamenu' ),
            'desc'        => '',
            'std'         => apply_filters('cool_megamenu_load_css', 'on'),
            'type'        => 'on-off',
            'section'     => 'crmm_general_settings',
            'condition'   => '',
            'operator'    => 'and',
        ),
        array(
            'id'          => 'crmm_megamenu_load_js',
            'label'       => __( 'Megamenu Javascript load', 'cool-megamenu' ),
            'desc'        => '',
            'std'         => apply_filters('cool_megamenu_load_js', 'on'),
            'type'        => 'on-off',
            'section'     => 'crmm_general_settings',
            'condition'   => '',
            'operator'    => 'and',
        ),  


      // responsive
      array(
            'id'          => 'crmm_megamenu_toggle_icon',
            'label'       => __( 'Toggle icon', 'cool-megamenu' ),
            'desc'        => '',
            'std'         => apply_filters('cool_megamenu_toggle_icon', 'on'),
            'type'        => 'on-off',
            'section'     => 'crmm_responsive_settings',
            'condition'   => '',
            'operator'    => 'and',
        ), 

    )
  );
  
  /* allow settings to be filtered before saving */
  $cool_megamenu_settings = apply_filters( crmm_settings_id() . '_args', $cool_megamenu_settings );
  
  /* settings are not the same update the DB */
  if ( $saved_settings !== $cool_megamenu_settings ) {
    update_option( crmm_settings_id(), $cool_megamenu_settings ); 
  }
  
  /* Lets OptionTree know the UI Builder is being overridden */
  global $crmm_has_cool_megamenu_options;
  $crmm_has_cool_megamenu_options = true;
  
}