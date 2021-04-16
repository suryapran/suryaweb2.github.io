<?php
/**
 * Plugin Name: Cool Responsive Megamenu
 * Plugin URI:  http://themeperch.net/cool-megamenu/
 * Description: Bootstrap4 based Mega menu
 * Version:     1.1.5.7
 * Author:      Themeperch
 * Author URI:  http://themeperch.net
 * License:     GPLv2 or later
 * Text Domain: cool-megamenu
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if(!defined('ABSPATH')) {
  die('You are not allowed to call this page directly.');
}

define( 'COOL_MEGAMENU_RELEASE_DATE', '16th November, 2013' );
define( 'COOL_MEGAMENU_UPDATE_DATE', '3rd August, 2019' );

/**
 * This is the Cool Megamenu loader class.
 */
if ( ! class_exists( 'Cool_Megamenu_Loader' ) ) {

  class Cool_Megamenu_Loader {
    
    /**
     * PHP5 constructor method.
     *
     * This method loads other methods of the class.
     *
     * @return    void
     *
     * @access    public
     */
    public function __construct() {
      
      /* load languages */
      $this->load_languages();
      
      /* load Cool Megamenu */
      add_action( 'after_setup_theme', array( $this, 'load_cool_megamenu' ), 1 );
      
    }
    
    /**
     * Load the languages before everything else.
     *
     * @return    void
     *
     * @access    private
     */
    private function load_languages() {
    
      /**
       * A quick check to see if we're in plugin mode.
       *
       */
      define( 'CRMM_PLUGIN_MODE', strpos( dirname( __FILE__ ), 'plugins' . DIRECTORY_SEPARATOR . basename( dirname( __FILE__ ) ) ) !== false ? true : false );
      
      /**
       * Path to the languages directory. 
       *
       * This path will be relative in plugin mode and absolute in theme mode.
       *
       */
      if ( CRMM_PLUGIN_MODE ) {
        
        define( 'CRMM_LANG_DIR', trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . trailingslashit( 'languages' ) );
        
      } else {
      
        if ( apply_filters( 'crmm_child_theme_mode', false ) == true ) {
        
          $path = @explode( get_stylesheet(), str_replace( '\\', '/', dirname( __FILE__ ) ) );
          $path = ltrim( end( $path ), '/' );
          define( 'CRMM_LANG_DIR', trailingslashit( trailingslashit( get_stylesheet_directory() ) . $path ) . trailingslashit( 'languages' ) . 'theme-mode' );
          
        } else {
          
          $path = @explode( get_template(), str_replace( '\\', '/', dirname( __FILE__ ) ) );
          $path = ltrim( end( $path ), '/' );
          define( 'CRMM_LANG_DIR', trailingslashit( trailingslashit( get_template_directory() ) . $path ) . trailingslashit( 'languages' ) . 'theme-mode' );
          
        }
      
      }

      /* load the text domain  */
      if ( CRMM_PLUGIN_MODE ) {
      
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        
      } else {
      
        add_action( 'after_setup_theme', array( $this, 'load_textdomain' ) );
        
      }
      
    }
    
    /**
     * Load the text domain.
     */
    public function load_textdomain() {
    
      if ( CRMM_PLUGIN_MODE ) {
      
        load_plugin_textdomain( 'cool-megamenu', false, CRMM_LANG_DIR );
        
      } else {
      
        load_theme_textdomain( 'cool-megamenu', CRMM_LANG_DIR );
        
      }
      
    }
    
    /** 
     * Load Cool Megamenu on the 'after_setup_theme' action. Then filters will 
     * be availble to the theme, and not only when in Theme Mode.
     */
    public function load_cool_megamenu() {
    
      /* setup the constants */
      $this->constants();
      
      /* include the required admin files */
      $this->admin_includes();
      
      /* include the required files */
      $this->includes();
      
      /* hook into WordPress */
      $this->hooks();
      
    }

    /**
     * Constants
     */
    private function constants() {
      
      /**
       * Current Version number.
       */
      define( 'CRMM_VERSION', '1.1.5.7' );
      
      /**
       * For developers: Theme mode.
       */
      define( 'CRMM_THEME_MODE', apply_filters( 'crmm_theme_mode', false ) );
      
      /**
       * For developers: Child Theme mode. TODO document
       */
      define( 'CRMM_CHILD_THEME_MODE', apply_filters( 'crmm_child_theme_mode', false ) );
      
      /**
       * For developers: Show Pages.
       */
      define( 'CRMM_SHOW_PAGES', apply_filters( 'crmm_show_pages', true ) );
      
      /**
       * For developers: Show Cool Megamenu Options UI Builder
       */
      define( 'CRMM_SHOW_OPTIONS_UI', apply_filters( 'crmm_show_options_ui', false ) );
      
      /**
       * For developers: Show Settings Import
       */
      define( 'CRMM_SHOW_SETTINGS_IMPORT', apply_filters( 'crmm_show_settings_import', false ) );
      
      /**
       * For developers: Show Settings Export
       */
      define( 'CRMM_SHOW_SETTINGS_EXPORT', apply_filters( 'crmm_show_settings_export', false ) );
      
      /**
       * For developers: Show New Layout.
       */
      define( 'CRMM_SHOW_NEW_LAYOUT', apply_filters( 'crmm_show_new_layout', false ) );
      
      /**
       * For developers: Show Documentation
       *
       * Run a filter and set to false if you want to hide the Documentation.
       * 
       */
      define( 'CRMM_SHOW_DOCS', apply_filters( 'crmm_show_docs', false ) );
      
      /**
       * For developers: Custom Theme Option page
       */
      define( 'CRMM_USE_THEME_OPTIONS', apply_filters( 'crmm_use_theme_options', true ) );
      
      /**
       * For developers: Meta Boxes.
       */
      define( 'CRMM_META_BOXES', apply_filters( 'crmm_meta_boxes', true ) );
      
      /**
       * For developers: Allow Unfiltered HTML in all the textareas.
       */
      define( 'CRMM_ALLOW_UNFILTERED_HTML', apply_filters( 'crmm_allow_unfiltered_html', false ) );
      
      /**
       * Check if in theme mode.
       */
      if ( false == CRMM_THEME_MODE && false == CRMM_CHILD_THEME_MODE ) {
        define( 'CRMM_DIR', plugin_dir_path( __FILE__ ) );
        define( 'CRMM_URL', plugin_dir_url( __FILE__ ) );
      } else {
        if ( true == CRMM_CHILD_THEME_MODE ) {
          $path = ltrim( end( @explode( get_stylesheet(), str_replace( '\\', '/', dirname( __FILE__ ) ) ) ), '/' );
          define( 'CRMM_DIR', trailingslashit( trailingslashit( get_stylesheet_directory() ) . $path ) );
          define( 'CRMM_URL', trailingslashit( trailingslashit( get_stylesheet_directory_uri() ) . $path ) );
        } else {
          $path = ltrim( end( @explode( get_template(), str_replace( '\\', '/', dirname( __FILE__ ) ) ) ), '/' );
          define( 'CRMM_DIR', trailingslashit( trailingslashit( get_template_directory() ) . $path ) );
          define( 'CRMM_URL', trailingslashit( trailingslashit( get_template_directory_uri() ) . $path ) );
        }
      }
      
      /**
       * Template directory URI for the current theme.
       *
       */
      if ( true == CRMM_CHILD_THEME_MODE ) {
        define( 'CRMM_THEME_URL', get_stylesheet_directory_uri() );
      } else {
        define( 'CRMM_THEME_URL', get_template_directory_uri() );
      }
      
    }
    
    /**
     * Include admin files
     *
     * These functions are included on admin pages only.
     */
    private function admin_includes() {
      
      /* exit early if we're not on an admin page */
      if ( ! is_admin() )
        return false;
      
      /* global include files */
      $files = array( 
        'crmm-functions-admin',
        'crmm-functions-option-types',
        'crmm-functions-compat',
        'crmm-settings-api',
        'cool-options'
      );
      
      /* include the meta box api */
      if ( CRMM_META_BOXES == true ) {
        $files[] = 'crmm-meta-box-api';
      }  
     
      
      /* include the settings & docs pages */
      if ( CRMM_SHOW_PAGES == true ) {
        $files[] = 'crmm-functions-settings-page';
        $files[] = 'crmm-functions-docs-page';
      }
      
      /* include the cleanup api */
      $files[] = 'crmm-cleanup-api';
      
      /* require the files */
      foreach ( $files as $file ) {
        $this->load_file( CRMM_DIR . "admin" . DIRECTORY_SEPARATOR . "{$file}.php" );
      }     
     
      
      /* Registers the Settings page */
      if ( CRMM_SHOW_PAGES == true ) {
        add_action( 'init', 'crmm_register_settings_page' );

        /* global CSS */
        add_action( 'admin_head', array( $this, 'global_admin_css' ) );
      }
      
    }
    
    /**
     * Include front-end files
     *
     * These functions are included on every page load 
     * incase other plugins need to access them.
     */
    private function includes() {
    
      $files = array( 
        'crmm_edit_custom_walker',        
        'class-walker-nav-menu',
        'crmm-functions',
        'crmm-filters',
        'crmm-scripts'
      );

      /* require the files */
      foreach ( $files as $file ) {
        $this->load_file( CRMM_DIR . "includes" . DIRECTORY_SEPARATOR . "{$file}.php" );
      }
      
    }
    
    /**
     * Execute the WordPress Hooks
     */
    private function hooks() {
      
      // Attempt to migrate the settings
      if ( function_exists( 'crmm_maybe_migrate_settings' ) )
        add_action( 'init', 'crmm_maybe_migrate_settings', 1 );
      
      // Attempt to migrate the Options
      if ( function_exists( 'crmm_maybe_migrate_options' ) )
        add_action( 'init', 'crmm_maybe_migrate_options', 1 );
      
      // Attempt to migrate the Layouts
      if ( function_exists( 'crmm_maybe_migrate_layouts' ) )
        add_action( 'init', 'crmm_maybe_migrate_layouts', 1 );

      /* load the Meta Box assets */
      if ( CRMM_META_BOXES == true ) {
      
        /* add scripts for metaboxes to post-new.php & post.php */
        add_action( 'admin_enqueue_scripts', 'crmm_admin_scripts', 11 );
              
        /* add styles for metaboxes to post-new.php & post.php */
        add_action( 'admin_enqueue_scripts', 'crmm_admin_styles', 11 );
      
      }
      
      /* Adds the Theme Option page to the admin bar */
      //add_action( 'admin_bar_menu', 'crmm_register_options_admin_bar_menu', 999 );
      
      /* prepares the after save do_action */
      add_action( 'admin_init', 'crmm_after_options_save', 1 );
      
      /* default settings */
      add_action( 'admin_init', 'crmm_default_settings', 2 );
      
      /* add xml to upload filetypes array */
      add_action( 'admin_init', 'crmm_add_xml_to_upload_filetypes', 3 );
      
      /* import */
      add_action( 'admin_init', 'crmm_import', 4 );
      
      /* export */
      add_action( 'admin_init', 'crmm_export', 5 );
      
      /* save settings */
      add_action( 'admin_init', 'crmm_save_settings', 6 );
      
      /* save layouts */
      add_action( 'admin_init', 'crmm_modify_layouts', 7 );
      
      /* create media post */
      add_action( 'admin_init', 'crmm_create_media_post', 8 );

      /* Google Fonts front-end CSS */
      add_action( 'wp_enqueue_scripts', 'crmm_load_google_fonts_css', 1 );
 
      /* dynamic front-end CSS */
      add_action( 'wp_enqueue_scripts', 'crmm_load_dynamic_css', 999 );

      /* insert theme CSS dynamically */
      add_action( 'crmm_after_options_save', 'crmm_save_css' );
      
      /* AJAX call to create a new section */
      add_action( 'wp_ajax_add_section', array( $this, 'add_section' ) );
      
      /* AJAX call to create a new setting */
      add_action( 'wp_ajax_add_setting', array( $this, 'add_setting' ) );
      
      /* AJAX call to create a new contextual help */
      add_action( 'wp_ajax_add_the_contextual_help', array( $this, 'add_the_contextual_help' ) );
      
      /* AJAX call to create a new choice */
      add_action( 'wp_ajax_add_choice', array( $this, 'add_choice' ) );
      
      /* AJAX call to create a new list item setting */
      add_action( 'wp_ajax_add_list_item_setting', array( $this, 'add_list_item_setting' ) );
      
      /* AJAX call to create a new layout */
      //add_action( 'wp_ajax_add_layout', array( $this, 'add_layout' ) );
      
      /* AJAX call to create a new list item */
      //add_action( 'wp_ajax_add_list_item', array( $this, 'add_list_item' ) );
      
      /* AJAX call to create a new social link */
      //add_action( 'wp_ajax_add_social_links', array( $this, 'add_social_links' ) );

      /* AJAX call to retrieve Google Font data */
      //add_action( 'wp_ajax_crmm_google_font', array( $this, 'retrieve_google_font' ) );
      
      // Adds the temporary hacktastic shortcode
      add_filter( 'media_view_settings', array( $this, 'shortcode' ), 10, 2 );
    
      // AJAX update
      add_action( 'wp_ajax_gallery_update', array( $this, 'ajax_gallery_update' ) );
      
      /* Modify the media uploader button */
      add_filter( 'gettext', array( $this, 'change_image_button' ), 10, 3 );
      
    }
    
    /**
     * Load a file
     */
    private function load_file( $file ){
      
      include_once( $file );
      
    }
    
    /**
     * Adds the global CSS to fix the menu icon.
     */
    public function global_admin_css() {
      global $wp_version;
      
      $wp_38plus = version_compare( $wp_version, '3.8', '>=' ) ? true : false;
      $fontsize = $wp_38plus ? '20px' : '16px';
      $wp_38minus = '';
      
      if ( ! $wp_38plus ) {
        $wp_38minus = '
        #adminmenu #toplevel_page_crmm-settings .menu-icon-generic div.wp-menu-image {
          background: none;
        }
        #adminmenu #toplevel_page_crmm-settings .menu-icon-generic div.wp-menu-image:before {
          padding-left: 6px;
        }';
      }

      echo '
      <style>
        
        #adminmenu #toplevel_page_crmm-settings .menu-icon-generic div.wp-menu-image:before {
          font: normal ' . $fontsize . '/1 "dashicons" !important;
          speak: none;
          padding: 6px 0;
          height: 34px;
          width: 20px;
          display: inline-block;
          -webkit-font-smoothing: antialiased;
          -moz-osx-font-smoothing: grayscale;
          -webkit-transition: all .1s ease-in-out;
          -moz-transition:    all .1s ease-in-out;
          transition:         all .1s ease-in-out;
        }
        #adminmenu #toplevel_page_crmm-settings .menu-icon-generic div.wp-menu-image:before {
          content: "\f480";
        }'  . $wp_38minus . '
      </style>
      ';
    }
    
    /**
     * AJAX utility function for adding a new section.
     */
    public function add_section() {
      echo crmm_sections_view( crmm_settings_id() . '[sections]', $_REQUEST['count'] );
      die();
    }
    
    /**
     * AJAX utility function for adding a new setting.
     */
    public function add_setting() {
      echo crmm_settings_view( $_REQUEST['name'], $_REQUEST['count'] );
      die();
    }
    
    /**
     * AJAX utility function for adding a new list item setting.
     */
    public function add_list_item_setting() {
      echo crmm_settings_view( $_REQUEST['name'] . '[settings]', $_REQUEST['count'] );
      die();
    }
    
    /**
     * AJAX utility function for adding new contextual help content.
     */
    public function add_the_contextual_help() {
      echo crmm_contextual_help_view( $_REQUEST['name'], $_REQUEST['count'] );
      die();
    }
    
    /**
     * AJAX utility function for adding a new choice.
     */
    public function add_choice() {
      echo crmm_choices_view( $_REQUEST['name'], $_REQUEST['count'] );
      die();
    }
    
    /**
     * AJAX utility function for adding a new layout.
     */
    public function add_layout() {
      echo crmm_layout_view( $_REQUEST['count'] );
      die();
    }
    
    /**
     * AJAX utility function for adding a new list item.
     */
    public function add_list_item() {
      check_ajax_referer( 'cool_megamenu', 'nonce' );
      crmm_list_item_view( $_REQUEST['name'], $_REQUEST['count'], array(), $_REQUEST['post_id'], $_REQUEST['get_option'], unserialize( crmm_decode( $_REQUEST['settings'] ) ), $_REQUEST['type'] );
      die();
    }
    
    /**
     * AJAX utility function for adding a new social link.
     */
    public function add_social_links() {
      check_ajax_referer( 'cool_megamenu', 'nonce' );
      crmm_social_links_view( $_REQUEST['name'], $_REQUEST['count'], array(), $_REQUEST['post_id'], $_REQUEST['get_option'], unserialize( crmm_decode( $_REQUEST['settings'] ) ), $_REQUEST['type'] );
      die();
    }
    
    /**
     * Fake the gallery shortcode
     */
    public function shortcode( $settings, $post ) {
      global $pagenow;

      if ( in_array( $pagenow, array( 'upload.php', 'customize.php' ) ) ) {
        return $settings;
      }

      // Set the Cool Megamenu post ID
      if ( ! is_object( $post ) ) {
        $post_id = isset( $_GET['post'] ) ? $_GET['post'] : ( isset( $_GET['post_ID'] ) ? $_GET['post_ID'] : 0 );
        if ( $post_id == 0 && function_exists( 'crmm_get_media_post_ID' ) ) {
          $post_id = crmm_get_media_post_ID();
        }
        $settings['post']['id'] = $post_id;
      }
      
      // No ID return settings
      if ( $settings['post']['id'] == 0 )
        return $settings;
  
      // Set the fake shortcode
      $settings['crmm_gallery'] = array( 'shortcode' => "[gallery id='{$settings['post']['id']}']" );
      
      // Return settings
      return $settings;
      
    }
    
    /**
     * Returns the AJAX images
     */
    public function ajax_gallery_update() {
    
      if ( ! empty( $_POST['ids'] ) )  {
        
        $return = '';
        
        foreach( $_POST['ids'] as $id ) {
        
          $thumbnail = wp_get_attachment_image_src( $id, 'thumbnail' );
          
          $return .= '<li><img  src="' . $thumbnail[0] . '" width="75" height="75" /></li>';
          
        }
        
        echo $return;
        exit();
      
      }
      
    }

    /**
     * Returns a JSON encoded Google fonts array.
     */
    public function retrieve_google_font() {

      if ( isset( $_POST['field_id'], $_POST['family'] ) ) {
        
        crmm_fetch_google_fonts();
        
        echo json_encode( array(
          'variants' => crmm_recognized_google_font_variants( $_POST['field_id'], $_POST['family'] ),
          'subsets'  => crmm_recognized_google_font_subsets( $_POST['field_id'], $_POST['family'] )
        ) );
        exit();

      }

    }
    
    /**
     * Filters the media uploader button.
     */
    public function change_image_button( $translation, $text, $domain ) {
      global $pagenow;
    
      if ( $pagenow == apply_filters( 'crmm_options_parent_slug', 'themes.php' ) && 'default' == $domain && 'Insert into post' == $text ) {
        
        // Once is enough.
        remove_filter( 'gettext', array( $this, 'crmm_change_image_button' ) );
        return apply_filters( 'crmm_upload_text', __( 'Send to Cool Megamenu', 'cool-megamenu' ) );
        
      }
      
      return $translation;
      
    }    
    
  }
  
  /**
   * Instantiate the Cool Megamenu loader class.
   *
   */
  $crmm_loader = new Cool_Megamenu_Loader();

}

/* End of file crmm-loader.php */