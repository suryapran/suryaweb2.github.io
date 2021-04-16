<?php if ( ! defined( 'CRMM_VERSION' ) ) exit( 'No direct script access allowed' );
/**
 * Cool Megamenu settings page functions.
 */

/**
 * Create option type.
 *
 * @return    string
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'crmm_type_theme_options_ui' ) ) {
  
  function crmm_type_theme_options_ui() {
    global $blog_id;
    
    echo '<form method="post" id="crmm-settings-form">';
      
      /* form nonce */
      wp_nonce_field( 'cool_megamenu_settings_form', 'cool_megamenu_settings_nonce' );
      
      /* format setting outer wrapper */
      echo '<div class="format-setting type-textblock has-desc">';
        
        /* description */
        echo '<div class="description">';
          
          echo '<h4>'. __( 'Warning!', 'cool-megamenu' ) . '</h4>';
          echo '<p class="warning">' . sprintf( __( 'Go to the %s page if you want to save data, this page is for adding settings.', 'cool-megamenu' ), '<a href="' . get_admin_url( $blog_id, apply_filters( 'crmm_options_parent_slug', 'themes.php' ) . '?page=' . apply_filters( 'crmm_options_menu_slug', 'crmm-options' ) ) . '"><code>Appearance->Cool Megamenu Options</code></a>' ) . '</p>';
          echo '<p class="warning">' . sprintf( __( 'If you\'re unsure or not completely positive that you should be editing these settings, you should read the %s first.', 'cool-megamenu' ), '<a href="' . get_admin_url( $blog_id, 'admin.php?page=crmm-documentation' ) . '"><code>Cool Megamenu->Documentation</code></a>' ) . '</p>';
          echo '<h4>'. __( 'Things could break or be improperly displayed to the end-user if you do one of the following:', 'cool-megamenu' ) . '</h4>';
          echo '<p class="warning">' . __( 'Give two sections the same ID, give two settings the same ID, give two contextual help content areas the same ID, don\'t create any settings, or have a section at the end of the settings list.', 'cool-megamenu' ) . '</p>';
          echo '<p>' . __( 'You can create as many settings as your project requires and use them how you see fit. When you add a setting here, it will be available on the Cool Megamenu Options page for use in your theme. To separate your settings into sections, click the "Add Section" button, fill in the input fields, and a new navigation menu item will be created.', 'cool-megamenu' ) . '</p>';
          echo '<p>' . __( 'All of the settings can be sorted and rearranged to your liking with Drag & Drop. Don\'t worry about the order in which you create your settings, you can always reorder them.', 'cool-megamenu' ) . '</p>';
          
        echo '</div>';
        
        /* get the saved settings */
        $settings = get_option( crmm_settings_id() );

        /* wrap settings array */
        echo '<div class="format-setting-inner">';
          
          /* set count to zero */
          $count = 0;
  
          /* loop through each section and its settings */
          echo '<ul class="crmm-setting-wrap crmm-sortable" id="cool_megamenu_settings_list" data-name="' . crmm_settings_id() . '[settings]">';
          
          if ( isset( $settings['sections'] ) ) {
          
            foreach( $settings['sections'] as $section ) {
              
              /* section */
              echo '<li class="' . ( $count == 0 ? 'ui-state-disabled' : 'ui-state-default' ) . ' list-section">' . crmm_sections_view( crmm_settings_id() . '[sections]', $count, $section ) . '</li>';
              
              /* increment item count */
              $count++;
              
              /* settings in this section */
              if ( isset( $settings['settings'] ) ) {
                
                foreach( $settings['settings'] as $setting ) {
                  
                  if ( isset( $setting['section'] ) && $setting['section'] == $section['id'] ) {
                    
                    echo '<li class="ui-state-default list-setting">' . crmm_settings_view( crmm_settings_id() . '[settings]', $count, $setting ) . '</li>';
                    
                    /* increment item count */
                    $count++;
                    
                  }
                  
                }
                
              }

            }
            
          }
          
          echo '</ul>';
          
          /* buttons */
          echo '<a href="javascript:void(0);" class="crmm-section-add crmm-ui-button button hug-left">' . __( 'Add Section', 'cool-megamenu' ) . '</a>';
          echo '<a href="javascript:void(0);" class="crmm-setting-add crmm-ui-button button">' . __( 'Add Setting', 'cool-megamenu' ) . '</a>';
          echo '<button class="crmm-ui-button button button-primary right hug-right">' . __( 'Save Changes', 'cool-megamenu' ) . '</button>';
          
          /* sidebar textarea */
          echo '
          <div class="format-setting-label" id="contextual-help-label">
            <h3 class="label">' . __( 'Contextual Help', 'cool-megamenu' ) . '</h3>
          </div>
          <div class="format-settings" id="contextual-help-setting">
            <div class="format-setting type-textarea no-desc">
              <div class="description"><strong>' . __( 'Contextual Help Sidebar', 'cool-megamenu' ) . '</strong>: ' . __( 'If you decide to add contextual help to the Theme Option page, enter the optional "Sidebar" HTML here. This would be an extremely useful place to add links to your themes documentation or support forum. Only after you\'ve added some content below will this display to the user.', 'cool-megamenu' ) . '</div>
              <div class="format-setting-inner">
                <textarea class="textarea" rows="10" cols="40" name="' . crmm_settings_id(). '[contextual_help][sidebar]">' . ( isset( $settings['contextual_help']['sidebar'] ) ? esc_html( $settings['contextual_help']['sidebar'] ) : '' ) . '</textarea>
              </div>
            </div>
          </div>';
          
          /* set count to zero */
          $count = 0;
          
          /* loop through each contextual_help content section */
          echo '<ul class="crmm-setting-wrap crmm-sortable" id="crmm_settings_help" data-name="' . crmm_settings_id(). '[contextual_help][content]">';
          
          if ( isset( $settings['contextual_help']['content'] ) ) {
          
            foreach( $settings['contextual_help']['content'] as $content ) {
              
              /* content */
              echo '<li class="ui-state-default list-contextual-help">' . crmm_contextual_help_view( crmm_settings_id() . '[contextual_help][content]',  $count, $content ) . '</li>';
              
              /* increment content count */
              $count++;

            }
            
          }
          
          echo '</ul>';

          echo '<a href="javascript:void(0);" class="crmm-help-add crmm-ui-button button hug-left">' . __( 'Add Contextual Help Content', 'cool-megamenu' ) . '</a>';
          echo '<button class="crmm-ui-button button button-primary right hug-right">' . __( 'Save Changes', 'cool-megamenu' ) . '</button>';

        echo '</div>';
        
      echo '</div>';
    
    echo '</form>';
    
  }
  
}

/**
 * Import XML option type.
 *
 * @return    string
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'crmm_type_import_xml' ) ) {
  
  function crmm_type_import_xml() {
    
    echo '<form method="post" id="import-xml-form">';
      
      /* form nonce */
      wp_nonce_field( 'import_xml_form', 'import_xml_nonce' );
      
      /* format setting outer wrapper */
      echo '<div class="format-setting type-textblock has-desc">';
        
        /* description */
        echo '<div class="description">';
          
          echo '<p class="deprecated">' . __( 'This import method has been deprecated. That means it has been replaced by a new method and is no longer supported, and may be removed from future versions. All themes that use this import method should be converted to use its replacement below.', 'cool-megamenu' ) . '</p>';
          
          echo '<p>' . sprintf( __( 'If you were given a Cool Megamenu Options XML file with a premium or free theme, locate it on your hard drive and upload that file by clicking the upload button. A popup window will appear, upload the XML file and click "%s". The file URL should be in the upload input, if it is click "Import XML".', 'cool-megamenu' ), apply_filters( 'crmm_upload_text', __( 'Send to Cool Megamenu', 'cool-megamenu' ) ) ) . '</p>';
          
          /* button */
          echo '<button class="crmm-ui-button button button-primary right hug-right">' . __( 'Import XML', 'cool-megamenu' ) . '</button>';
          
        echo '</div>';
        
        echo '<div class="format-setting-inner">';
          
          /* build upload */
          echo '<div class="crmm-ui-upload-parent">';
            
            /* input */
            echo '<input type="text" name="import_xml" id="import_xml" value="" class="widefat crmm-ui-upload-input" />';
            
            /* get media post_id */
            $post_id = ( $id = crmm_get_media_post_ID() ) ? (int) $id : 0;
          
            /* add xml button */
            echo '<a href="javascript:void(0);" class="crmm_upload_media crmm-ui-button button button-primary light" rel="' . $post_id . '" title="' . __( 'Add XML', 'cool-megamenu' ) . '"><span class="icon crmm-icon-plus-circle"></span>' . __( 'Add XML', 'cool-megamenu' ) . '</a>';
          
          echo '</div>';
          
        echo '</div>';
        
      echo '</div>';
      
    echo '</form>';
    
  }
  
}

/**
 * Import Settings option type.
 *
 * @return    string
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'crmm_type_import_settings' ) ) {
  
  function crmm_type_import_settings() {
    
    echo '<form method="post" id="import-settings-form">';
      
      /* form nonce */
      wp_nonce_field( 'import_settings_form', 'import_settings_nonce' );
      
      /* format setting outer wrapper */
      echo '<div class="format-setting type-textarea has-desc">';
           
        /* description */
        echo '<div class="description">';
          
          echo '<p>' . __( 'To import your Settings copy and paste what appears to be a random string of alpha numeric characters into this textarea and press the "Import Settings" button.', 'cool-megamenu' ) . '</p>';
          
          /* button */
          echo '<button class="crmm-ui-button button button-primary right hug-right">' . __( 'Import Settings', 'cool-megamenu' ) . '</button>';
          
        echo '</div>';
        
        /* textarea */
        echo '<div class="format-setting-inner">';
          
          echo '<textarea rows="10" cols="40" name="import_settings" id="import_settings" class="textarea"></textarea>';

        echo '</div>';
        
      echo '</div>';
    
    echo '</form>';
    
  }
  
}

/**
 * Import Data option type.
 *
 * @return    string
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'crmm_type_import_data' ) ) {
  
  function crmm_type_import_data() {
    
    echo '<form method="post" id="import-data-form">';
      
      /* form nonce */
      wp_nonce_field( 'import_data_form', 'import_data_nonce' );
        
      /* format setting outer wrapper */
      echo '<div class="format-setting type-textarea has-desc">';
        
        /* description */
        echo '<div class="description">';
          
          if ( CRMM_SHOW_SETTINGS_IMPORT ) echo '<p>' . __( 'Only after you\'ve imported the Settings should you try and update your Cool Megamenu Options.', 'cool-megamenu' ) . '</p>';
          
          echo '<p>' . __( 'To import your Cool Megamenu Options copy and paste what appears to be a random string of alpha numeric characters into this textarea and press the "Import Cool Megamenu Options" button.', 'cool-megamenu' ) . '</p>';
          
          /* button */
          echo '<button class="crmm-ui-button button button-primary right hug-right">' . __( 'Import Cool Megamenu Options', 'cool-megamenu' ) . '</button>';
          
        echo '</div>';
        
        /* textarea */
        echo '<div class="format-setting-inner">';
          
          echo '<textarea rows="10" cols="40" name="import_data" id="import_data" class="textarea"></textarea>';

        echo '</div>';
        
      echo '</div>';
    
    echo '</form>';
    
  }
  
}

/**
 * Import Layouts option type.
 *
 * @return    string
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'crmm_type_import_layouts' ) ) {
  
  function crmm_type_import_layouts() {
    
    echo '<form method="post" id="import-layouts-form">';
      
      /* form nonce */
      wp_nonce_field( 'import_layouts_form', 'import_layouts_nonce' );
      
      /* format setting outer wrapper */
      echo '<div class="format-setting type-textarea has-desc">';
        
        /* description */
        echo '<div class="description">';
          
          if ( CRMM_SHOW_SETTINGS_IMPORT ) echo '<p>' . __( 'Only after you\'ve imported the Settings should you try and update your Layouts.', 'cool-megamenu' ) . '</p>';
          
          echo '<p>' . __( 'To import your Layouts copy and paste what appears to be a random string of alpha numeric characters into this textarea and press the "Import Layouts" button. Keep in mind that when you import your layouts, the active layout\'s saved data will write over the current data set for your Cool Megamenu Options.', 'cool-megamenu' ) . '</p>';
          
          /* button */
          echo '<button class="crmm-ui-button button button-primary right hug-right">' . __( 'Import Layouts', 'cool-megamenu' ) . '</button>';
          
        echo '</div>';
        
        /* textarea */
        echo '<div class="format-setting-inner">';
          
          echo '<textarea rows="10" cols="40" name="import_layouts" id="import_layouts" class="textarea"></textarea>';
  
        echo '</div>';
        
      echo '</div>';
      
    echo '</form>';
    
  }
  
}

/**
 * Export Settings File option type.
 *
 * @return    string
 *
 * @access    public
 */
if ( ! function_exists( 'crmm_type_export_settings_file' ) ) {
  
  function crmm_type_export_settings_file() {
    global $blog_id;
    
    echo '<form method="post" id="export-settings-file-form">';
    
      /* form nonce */
      wp_nonce_field( 'export_settings_file_form', 'export_settings_file_nonce' );
      
      /* format setting outer wrapper */
      echo '<div class="format-setting type-textarea simple has-desc">';
        
        /* description */
        echo '<div class="description">';
          
          echo '<p>' . sprintf( __( 'Export your Settings into a fully functional %s file. If you want to add your own custom %s text domain to the file, enter it into the text field before exporting. For more information on how to use this file read the documentation on %s. Remember, you should always check the file for errors before including it in your theme.', 'cool-megamenu' ), '<code>theme-options.php</code>', '<a href="http://codex.wordpress.org/I18n_for_WordPress_Developers" target="_blank">I18n</a>', '<a href="' . get_admin_url( $blog_id, 'admin.php?page=crmm-documentation#section_theme_mode' ) . '">' . __( 'Theme Mode', 'cool-megamenu' ) . '</a>' ) . '</p>';
          
        echo '</div>';
          
        echo '<div class="format-setting-inner">';
          
          echo '<input type="text" name="domain" value="" class="widefat crmm-ui-input" placeholder="text-domain" autocomplete="off" />';
          
          /* button */
          echo '<button class="crmm-ui-button button button-primary hug-left">' . __( 'Export Settings File', 'cool-megamenu' ) . '</button>';
          
        echo '</div>';
        
      echo '</div>';
    
    echo '</form>';
    
  }
  
}

/**
 * Export Settings option type.
 *
 * @return    string
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'crmm_type_export_settings' ) ) {
  
  function crmm_type_export_settings() {
    
    /* format setting outer wrapper */
    echo '<div class="format-setting type-textarea simple has-desc">';
      
      /* description */
      echo '<div class="description">';
        
        echo '<p>' . __( 'Export your Settings by highlighting this text and doing a copy/paste into a blank .txt file. Then save the file for importing into another install of WordPress later. Alternatively, you could just paste it into the <code>Cool Megamenu->Settings->Import</code> <strong>Settings</strong> textarea on another web site.', 'cool-megamenu' ) . '</p>';
        
      echo '</div>';
        
      /* get theme options data */
      $settings = get_option( crmm_settings_id() );
      $settings = ! empty( $settings ) ?  crmm_encode( serialize( $settings ) ) : '';
        
      echo '<div class="format-setting-inner">';
        echo '<textarea rows="10" cols="40" name="export_settings" id="export_settings" class="textarea">' . $settings . '</textarea>';
      echo '</div>';
      
    echo '</div>';
    
  }
  
}

/**
 * Export Data option type.
 *
 * @return    string
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'crmm_type_export_data' ) ) {
  
  function crmm_type_export_data() {
    
    /* format setting outer wrapper */
    echo '<div class="format-setting type-textarea simple has-desc">';
      
      /* description */
      echo '<div class="description">';
        
        echo '<p>' . __( 'Export your Cool Megamenu Options data by highlighting this text and doing a copy/paste into a blank .txt file. Then save the file for importing into another install of WordPress later. Alternatively, you could just paste it into the <code>Cool Megamenu->Settings->Import</code> <strong>Cool Megamenu Options</strong> textarea on another web site.', 'cool-megamenu' ) . '</p>';
        
      echo '</div>';
      
      /* get theme options data */
      $data = get_option( crmm_options_id() );
      $data = ! empty( $data ) ? crmm_encode( serialize( $data ) ) : '';
        
      echo '<div class="format-setting-inner">';
        echo '<textarea rows="10" cols="40" name="export_data" id="export_data" class="textarea">' . $data . '</textarea>';
      echo '</div>';
      
    echo '</div>';
    
  }
  
}

/**
 * Export Layouts option type.
 *
 * @return    string
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'crmm_type_export_layouts' ) ) {
  
  function crmm_type_export_layouts() {
    
    /* format setting outer wrapper */
    echo '<div class="format-setting type-textarea simple has-desc">';
      
      /* description */
      echo '<div class="description">';
        
        echo '<p>' . __( 'Export your Layouts by highlighting this text and doing a copy/paste into a blank .txt file. Then save the file for importing into another install of WordPress later. Alternatively, you could just paste it into the <code>Cool Megamenu->Settings->Import</code> <strong>Layouts</strong> textarea on another web site.', 'cool-megamenu' ) . '</p>';
        
        
      echo '</div>';
      
      /* get layout data */
      $layouts = get_option( crmm_layouts_id() );
      $layouts = ! empty( $layouts ) ? crmm_encode( serialize( $layouts ) ) : '';
        
      echo '<div class="format-setting-inner">';
        echo '<textarea rows="10" cols="40" name="export_layouts" id="export_layouts" class="textarea">' . $layouts . '</textarea>';
      echo '</div>';
      
    echo '</div>';
    
  }
  
}

/**
 * Modify Layouts option type.
 *
 * @return    string
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'crmm_type_modify_layouts' ) ) {
  
  function crmm_type_modify_layouts() {
    
    echo '<form method="post" id="crmm-settings-form">';
      
      /* form nonce */
      wp_nonce_field( 'cool_megamenu_modify_layouts_form', 'cool_megamenu_modify_layouts_nonce' );

      /* format setting outer wrapper */
      echo '<div class="format-setting type-textarea has-desc">';
          
        /* description */
        echo '<div class="description">';
          
          echo '<p>' . __( 'To add a new layout enter a unique lower case alphanumeric string (dashes allowed) in the text field and click "Save Layouts".', 'cool-megamenu' ) . '</p>';
          echo '<p>' . __( 'As well, you can activate, remove, and drag & drop the order; all situations require you to click "Save Layouts" for the changes to be applied.', 'cool-megamenu' ) . '</p>';
          echo '<p>' . __( 'When you create a new layout it will become active and any changes made to the Cool Megamenu Options will be applied to it. If you switch back to a different layout immediately after creating a new layout that new layout will have a snapshot of the current Cool Megamenu Options data attached to it.', 'cool-megamenu' ) . '</p>';
          if ( CRMM_SHOW_DOCS ) echo '<p>' . __( 'Visit <code>Cool Megamenu->Documentation->Layouts Overview</code> to see a more in-depth description of what layouts are and how to use them.', 'cool-megamenu' ) . '</p>';
          
        echo '</div>';
        
        echo '<div class="format-setting-inner">';
 
          /* get the saved layouts */
          $layouts = get_option( crmm_layouts_id() );
      
          /* set active layout */
          $active_layout = isset( $layouts['active_layout'] ) ? $layouts['active_layout'] : '';
          
          echo '<input type="hidden" name="' . crmm_layouts_id() . '[active_layout]" value="' . esc_attr( $active_layout ) . '" class="active-layout-input" />';
          
          /* add new layout */
          echo '<input type="text" name="' . crmm_layouts_id() . '[_add_new_layout_]" value="" class="widefat crmm-ui-input" autocomplete="off" />';
           
          /* loop through each layout */
          echo '<ul class="crmm-setting-wrap crmm-sortable" id="cool_megamenu_layouts">';
          
          if ( is_array( $layouts ) && ! empty( $layouts ) ) {
          
            foreach( $layouts as $key => $data ) {
              
              /* skip active layout array */
              if ( $key == 'active_layout' )
                continue;
                
              /* content */
              echo '<li class="ui-state-default list-layouts">' . crmm_layout_view( $key, $data, $active_layout ) . '</li>';

            }
            
          }
          
          echo '</ul>';
            
          echo '<button class="crmm-ui-button button button-primary right hug-right">' . __( 'Save Layouts', 'cool-megamenu' ) . '</button>';
            
        echo '</div>';
        
      echo '</div>';
    
    echo '</form>';
    
  }
  
}

/* End of file crmm-functions-settings-page.php */