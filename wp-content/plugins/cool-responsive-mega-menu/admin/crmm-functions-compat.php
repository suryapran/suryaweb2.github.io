<?php if ( ! defined( 'CRMM_VERSION' ) ) exit( 'No direct script access allowed' );
/**
 * Compatibility Functions.
 * 
 */

/* run the actions & filters */
add_action( 'admin_init',                         'compat_crmm_import_from_files', 1 );
add_filter( 'crmm_option_types_array',              'compat_crmm_option_types_array', 10, 1 );
add_filter( 'crmm_recognized_font_styles',          'compat_crmm_recognized_font_styles', 10, 2 );
add_filter( 'crmm_recognized_font_weights',         'compat_crmm_recognized_font_weights', 10, 2 );
add_filter( 'crmm_recognized_font_variants',        'compat_crmm_recognized_font_variants', 10, 2 );
add_filter( 'crmm_recognized_font_families',        'compat_crmm_recognized_font_families', 10, 2 );
add_filter( 'crmm_recognized_background_repeat',    'compat_crmm_recognized_background_repeat', 10, 2 );
add_filter( 'crmm_recognized_background_position',  'compat_crmm_recognized_background_position', 10, 2 );
add_filter( 'crmm_measurement_unit_types',          'compat_crmm_measurement_unit_types', 10, 2 );

/**
 * Import from the old 1.x files for backwards compatibility.
 *
 * @return    void
 *
 * @access    private
 * .8
 */
if ( ! function_exists( 'compat_crmm_import_from_files' ) ) {

  function compat_crmm_import_from_files() {
    
    /* file path & name without extention */
    $crmm_xml     = '/cool-responsive-mega-menu/cool-megamenu-options.xml';
    $crmm_data    = '/cool-responsive-mega-menu/cool-megamenu-options.txt';
    $crmm_layout  = '/cool-responsive-mega-menu/layouts.txt';
    
    /* XML file path - child theme first then parent */
    if ( is_readable( get_stylesheet_directory() . $crmm_xml ) ) {
    
      $xml_file = get_stylesheet_directory_uri() . $crmm_xml;
    
    } else if ( is_readable( get_template_directory() . $crmm_xml ) ) {
    
      $xml_file = get_template_directory_uri() . $crmm_xml;
    
    }
    
    /* Data file path - child theme first then parent */
    if ( is_readable( get_stylesheet_directory() . $crmm_data ) ) {
    
      $data_file = get_stylesheet_directory_uri() . $crmm_data;
    
    } else if ( is_readable( get_template_directory() . $crmm_data ) ) {
    
      $data_file = get_template_directory_uri() . $crmm_data;
    
    }
    
    /* Layout file path - child theme first then parent */
    if ( is_readable( get_stylesheet_directory() . $crmm_layout ) ) {
    
      $layout_file = get_stylesheet_directory_uri() . $crmm_layout;
    
    } else if ( is_readable( get_template_directory() . $crmm_layout ) ) {
    
      $layout_file = get_template_directory_uri() . $crmm_layout;
    
    }
    
    /* check for files */
    $has_xml    = isset( $xml_file ) ? true : false;
    $has_data   = isset( $data_file ) ? true : false;
    $has_layout = isset( $layout_file ) ? true : false;
    
    /* auto import XML file */
    if ( $has_xml == true && ! get_option( crmm_settings_id() ) && class_exists( 'SimpleXMLElement' ) ) {
    
      $settings = crmm_import_xml( $xml_file );
      
      if ( isset( $settings ) && ! empty( $settings ) ) {
        
        update_option( crmm_settings_id(), $settings );
        
      }
      
    }
    
    /* auto import Data file */
    if ( $has_data == true && ! get_option( crmm_options_id() ) ) {
      
      $get_data = wp_remote_get( $data_file );
      
      if ( is_wp_error( $get_data ) )
        return false;
        
      $rawdata = isset( $get_data['body'] ) ? $get_data['body'] : '';
      $options = unserialize( crmm_decode( $rawdata ) );
      
      /* get settings array */
      $settings = get_option( crmm_settings_id() );
      
      /* has options */
      if ( is_array( $options ) ) {
        
        /* validate options */
        if ( is_array( $settings ) ) {
        
          foreach( $settings['settings'] as $setting ) {
          
            if ( isset( $options[$setting['id']] ) ) {
              
              $content = crmm_stripslashes( $options[$setting['id']] );
              
              $options[$setting['id']] = crmm_validate_setting( $content, $setting['type'], $setting['id'] );
              
            }
          
          }
        
        }
        
        /* update the option tree array */
        update_option( crmm_options_id(), $options );
        
      }
      
    }
    
    /* auto import Layout file */
    if ( $has_layout == true && ! get_option( crmm_layouts_id() ) ) {
    
      $get_data = wp_remote_get( $layout_file );
      
      if ( is_wp_error( $get_data ) )
        return false;
        
      $rawdata = isset( $get_data['body'] ) ? $get_data['body'] : '';
      $layouts = unserialize( crmm_decode( $rawdata ) );
      
      /* get settings array */
      $settings = get_option( crmm_settings_id() );
      
      /* has layouts */
      if ( is_array( $layouts ) ) {
        
        /* validate options */
        if ( is_array( $settings ) ) {
          
          foreach( $layouts as $key => $value ) {
            
            if ( $key == 'active_layout' )
              continue;
              
            $options = unserialize( crmm_decode( $value ) );
            
            foreach( $settings['settings'] as $setting ) {

              if ( isset( $options[$setting['id']] ) ) {
                
                $content = crmm_stripslashes( $options[$setting['id']] );
                
                $options[$setting['id']] = crmm_validate_setting( $content, $setting['type'], $setting['id'] );
                
              }
            
            }

            $layouts[$key] = crmm_encode( serialize( $options ) );
          
          }
        
        }
        
        /* update the option tree array */
        if ( isset( $layouts['active_layout'] ) ) {
        
          update_option( crmm_options_id(), unserialize( crmm_decode( $layouts[$layouts['active_layout']] ) ) );
          
        }
        
        /* update the option tree layouts array */
        update_option( crmm_layouts_id(), $layouts );
        
      }
      
    }
    
  }

}

/**
 * Filters the option types array.
 *
 * Allows the old 'cool_megamenu_option_types' filter to 
 * change the new 'crmm_option_types_array' return value.
 *
 * @return    array
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'compat_crmm_option_types_array' ) ) {

  function compat_crmm_option_types_array( $array ) {
  
    return apply_filters( 'cool_megamenu_option_types', $array );
    
  }

}

/**
 * Filters the recognized font styles array.
 *
 * Allows the old 'recognized_font_styles' filter to 
 * change the new 'crmm_recognized_font_styles' return value.
 *
 * @return    array
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'compat_crmm_recognized_font_styles' ) ) {

  function compat_crmm_recognized_font_styles( $array, $id ) {
  
    return apply_filters( 'recognized_font_styles', $array, $id );
    
  }
  
}

/**
 * Filters the recognized font weights array.
 *
 * Allows the old 'recognized_font_weights' filter to 
 * change the new 'crmm_recognized_font_weights' return value.
 *
 * @return    array
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'compat_crmm_recognized_font_weights' ) ) {

  function compat_crmm_recognized_font_weights( $array, $id ) {
  
    return apply_filters( 'recognized_font_weights', $array, $id );
    
  }
  
}

/**
 * Filters the recognized font variants array.
 *
 * Allows the old 'recognized_font_variants' filter to 
 * change the new 'crmm_recognized_font_variants' return value.
 *
 * @return    array
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'compat_crmm_recognized_font_variants' ) ) {

  function compat_crmm_recognized_font_variants( $array, $id ) {
  
    return apply_filters( 'recognized_font_variants', $array, $id );
    
  }
  
}

/**
 * Filters the recognized font families array.
 *
 * Allows the old 'recognized_font_families' filter to 
 * change the new 'crmm_recognized_font_families' return value.
 *
 * @return    array
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'compat_crmm_recognized_font_families' ) ) {

  function compat_crmm_recognized_font_families( $array, $id ) {
  
    return apply_filters( 'recognized_font_families', $array, $id );
    
  }
  
}

/**
 * Filters the recognized background repeat array.
 *
 * Allows the old 'recognized_background_repeat' filter to 
 * change the new 'crmm_recognized_background_repeat' return value.
 *
 * @return    array
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'compat_crmm_recognized_background_repeat' ) ) {

  function compat_crmm_recognized_background_repeat( $array, $id ) {
  
    return apply_filters( 'recognized_background_repeat', $array, $id );
    
  }
  
}

/**
 * Filters the recognized background position array.
 *
 * Allows the old 'recognized_background_position' filter to 
 * change the new 'crmm_recognized_background_position' return value.
 *
 * @return    array
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'compat_crmm_recognized_background_position' ) ) {

  function compat_crmm_recognized_background_position( $array, $id ) {
  
    return apply_filters( 'recognized_background_position', $array, $id );
    
  }
  
}

/**
 * Filters the measurement unit types array.
 *
 * Allows the old 'measurement_unit_types' filter to 
 * change the new 'crmm_measurement_unit_types' return value.
 *
 * @return    array
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'compat_crmm_measurement_unit_types' ) ) {

  function compat_crmm_measurement_unit_types( $array, $id ) {
  
    return apply_filters( 'measurement_unit_types', $array, $id );
    
  }
  
}


/* End of file crmm-functions-compat.php */