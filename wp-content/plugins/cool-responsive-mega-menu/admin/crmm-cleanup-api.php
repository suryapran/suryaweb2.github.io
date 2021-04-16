<?php if ( ! defined( 'CRMM_VERSION' ) ) exit( 'No direct script access allowed' );
/**
 * Cool Megamenu Cleanup API
 *
 * This class loads all the Cool Megamenu Cleanup methods and helpers.
 *
 */
if ( ! class_exists( 'CRMM_Cleanup' ) ) {

  class CRMM_Cleanup {
  
    /**
     * PHP5 constructor method.
     *
     * This method adds other methods of the class to specific hooks within WordPress.
     *
     * @uses      add_action()
     *
     * @return    void
     *
     * @access    public
     */
    function __construct() {
      if ( ! is_admin() )
        return;

      // Load styles
      add_action( 'admin_head', array( $this, 'styles' ), 1 );
      
      // Maybe Clean up Cool Megamenu
      add_action( 'admin_menu', array( $this, 'maybe_cleanup' ), 100 );
      
      // Increase timeout if allowed
      add_action( 'crmm_pre_consolidate_posts', array( $this, 'increase_timeout' ) );
      
    }
    
    /**
     * Adds the cleanup styles to the admin head
     *
     * @return    string
     *
     * @access    public
     */
    function styles() {

      echo '<style>#toplevel_page_crmm-cleanup{display:none;}</style>';

    }
    
    /**
     * Check if Cool Megamenu needs to be cleaned up from a previous install.
     *
     * @return    void
     *
     * @access    public
     */
    public function maybe_cleanup() {
      global $wpdb, $crmm_maybe_cleanup_posts, $crmm_maybe_cleanup_table;

      $table_name = $wpdb->prefix . 'cool_megamenu';
      $page = isset( $_GET['page'] ) ? $_GET['page'] : '';
      $crmm_maybe_cleanup_posts = count( $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_type = 'cool-megamenu' LIMIT 2" ) ) > 1;
      $crmm_maybe_cleanup_table = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) == $table_name;

      if ( ! $crmm_maybe_cleanup_posts && ! $crmm_maybe_cleanup_table && $page == 'crmm-cleanup' ) {
        wp_redirect( apply_filters( 'crmm_options_parent_slug', 'themes.php' ) . '?page=' . apply_filters( 'crmm_options_menu_slug', 'crmm-options' ) );
        exit;
      }

      if ( $crmm_maybe_cleanup_posts || $crmm_maybe_cleanup_table ) {
        
        if ( $page != 'crmm-cleanup' )
          add_action( 'admin_notices', array( $this, 'cleanup_notice' ) );

        $theme_check_bs = 'add_menu_' . 'page';

        $theme_check_bs( apply_filters( 'crmm_cleanup_page_title', __( 'Cool Megamenu Cleanup', 'cool-megamenu' ) ), apply_filters( 'crmm_cleanup_menu_title', __( 'Cool Megamenu Cleanup', 'cool-megamenu' ) ), 'edit_theme_options', 'crmm-cleanup', array( $this, 'options_page' ) );

      }
      
    }
    
    /**
     * Adds an admin nag.
     *
     * @return    string
     *
     * @access    public
     */
    public function cleanup_notice() {

      if ( get_current_screen()->id != 'appearance_page_crmm-cleanup' )
        echo '<div class="update-nag">' . sprintf( __( 'Cool Megamenu has outdated data that should be removed. Please go to %s for more information.', 'cool-megamenu' ), sprintf( '<a href="%s">%s</a>', admin_url( 'themes.php?page=crmm-cleanup' ), apply_filters( 'crmm_cleanup_menu_title', __( 'Cool Megamenu Cleanup', 'cool-megamenu' ) ) ) ) . '</div>';
    
    }

    /**
     * Adds a Tools sub page to clean up the database with.
     *
     * @return    string
     *
     * @access    public
     */
    public function options_page() {
      global $wpdb, $crmm_maybe_cleanup_posts, $crmm_maybe_cleanup_table;

      // Option ID
      $option_id = 'crmm_media_post_ID';

      // Get the media post ID
      $post_ID = get_option( $option_id, false );

      // Zero loop count
      $count = 0;

      // Check for safe mode
      $safe_mode = ini_get( 'safe_mode' );

      echo '<div class="wrap">';

        echo '<h2>' . apply_filters( 'crmm_cleanup_page_title', __( 'Cool Megamenu Cleanup', 'cool-megamenu' ) ) . '</h2>';

        if ( $crmm_maybe_cleanup_posts ) {

          $posts = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_type = 'cool-megamenu'" );

          echo '<h3>' . __( 'Multiple Media Posts', 'cool-megamenu' ) . '</h3>';

          echo '<p>' . sprintf( __( 'There are currently %s Cool Megamenu media posts in your database. At some point in the past, a version of Cool Megamenu added multiple %s media post objects cluttering up your %s table. There is no associated risk or harm that these posts have caused other than to add size to your overall database. Thankfully, there is a way to remove all these orphaned media posts and get your database cleaned up.', 'cool-megamenu' ), '<code>' . number_format( count( $posts ) ) . '</code>', '<tt>option-tree</tt>', '<tt>' . $wpdb->posts . '</tt>' ) . '</p>';

          echo '<p>' . sprintf( __( 'By clicking the button below, Cool Megamenu will delete %s records and consolidate them into one single Cool Megamenu media post for uploading attachments to. Additionally, the attachments will have their parent ID updated to the correct media post.', 'cool-megamenu' ), '<code>' . number_format( count( $posts ) - 1 ) . '</code>' ) . '</p>';

          echo '<p><strong>' . __( 'This could take a while to fully process depending on how many records you have in your database, so please be patient and wait for the script to finish.', 'cool-megamenu' ) . '</strong></p>';

          echo $safe_mode ?  '<p>' . sprintf( __( '%s Your server is running in safe mode. Which means this page will automatically reload after deleting %s posts, you can filter this number using %s if your server is having trouble processing that many at one time.', 'cool-megamenu' ), '<strong>Note</strong>:', apply_filters( 'crmm_consolidate_posts_reload', 500 ), '<tt>crmm_consolidate_posts_reload</tt>' ) . '</p>' : '';

          echo '<p><a class="button button-primary" href="' . wp_nonce_url( admin_url( 'themes.php?page=crmm-cleanup' ), 'consolidate-posts' ) . '">' . __( 'Consolidate Posts', 'cool-megamenu' ) . '</a></p>';

          if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'consolidate-posts' ) ) {

            if ( $post_ID === false || empty( $post_ID ) ) {
              $post_ID = isset( $posts[0]->ID ) ? $posts[0]->ID : null;

              // Add to the DB
              if ( $post_ID !== null )
                update_option( $option_id, $post_ID );

            }

            // Do pre consolidation action to increase timeout.
            do_action( 'crmm_pre_consolidate_posts' );

            // Loop over posts
            foreach( $posts as $post ) {

              // Don't destroy the correct post.
              if ( $post_ID == $post->ID )
                continue;

              // Update count
              $count++;

              // Reload script in safe mode
              if ( $safe_mode && $count > apply_filters( 'crmm_consolidate_posts_reload', 500 ) ) {
                echo '<br />' . __( 'Reloading...', 'cool-megamenu' );
                echo '
                <script>
                  setTimeout( crmm_script_reload, 3000 )
                  function crmm_script_reload() {
                    window.location = "' . self_admin_url( 'themes.php?page=crmm-cleanup&_wpnonce=' . wp_create_nonce( 'consolidate-posts' ) ) . '"
                  }
                </script>';
                break;
              }
 
              // Get the attachements
              $attachments = get_children( 'post_type=attachment&post_parent=' . $post->ID );

              // Update the attachments parent ID
              if ( ! empty( $attachments ) ) {

                echo 'Updating Attachments parent ID for <tt>option-tree</tt> post <tt>#' . $post->ID . '</tt>.<br />';

                foreach( $attachments as $attachment_id => $attachment ) {
                  wp_update_post( 
                    array(
                      'ID' => $attachment_id,
                      'post_parent' => $post_ID
                    )
                  );
                }

              }

              // Delete post
              echo 'Deleting <tt>option-tree</tt> post <tt>#' . $post->ID . '</tt><br />';
              wp_delete_post( $post->ID, true );

            }

            echo '<br />' . __( 'Clean up script has completed, the page will now reload...', 'cool-megamenu' );

            echo '
            <script>
              setTimeout( crmm_script_reload, 3000 )
              function crmm_script_reload() {
                window.location = "' . self_admin_url( 'themes.php?page=crmm-cleanup' ) . '"
              }
            </script>';

          }

        }

        if ( $crmm_maybe_cleanup_table ) {

          $table_name = $wpdb->prefix . 'cool_megamenu';

          echo $crmm_maybe_cleanup_posts ? '<hr />' : '';

          echo '<h3>' . __( 'Outdated Table', 'cool-megamenu' ) . '</h3>';

          echo '<p>' . sprintf( __( 'If you have upgraded from an old 1.x version of Cool Megamenu at some point, you have an extra %s table in your database that can be removed. It\'s not hurting anything, but does not need to be there. If you want to remove it. Click the button below.', 'cool-megamenu' ), '<tt>' . $table_name . '</tt>' ) . '</p>';

          echo '<p><a class="button button-primary" href="' . wp_nonce_url( admin_url( 'themes.php?page=crmm-cleanup' ), 'drop-table' ) . '">' . __( 'Drop Table', 'cool-megamenu' ) . '</a></p>';

          if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'drop-table' ) ) {

            echo '<p>' . sprintf( __( 'Deleting the outdated and unused %s table...', 'cool-megamenu' ), '<tt>' . $table_name . '</tt>' ) . '</p>';

            $wpdb->query( "DROP TABLE IF EXISTS $table_name" );

            if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) != $table_name ) {

              echo '<p>' . sprintf( __( 'The %s table has been successfully deleted. The page will now reload...', 'cool-megamenu' ), '<tt>' . $table_name . '</tt>' ) . '</p>';

              echo '
              <script>
                setTimeout( crmm_script_reload, 3000 )
                function crmm_script_reload() {
                  window.location = "' . self_admin_url( 'themes.php?page=crmm-cleanup' ) . '"
                }
              </script>';

            } else {

              echo '<p>' . sprintf( __( 'Something went wrong. The %s table was not deleted.', 'cool-megamenu' ), '<tt>' . $table_name . '</tt>' ) . '</p>';

            }

          }

        }
 
      echo '</div>';

    }

    /**
     * Increase PHP timeout.
     *
     * This is to prevent bulk operations from timing out
     *
     * @return    void
     *
     * @access    public
     */
    public function increase_timeout() {
      
      if ( ! ini_get( 'safe_mode' ) ) {
      
        @set_time_limit( 0 );
        
      }
      
    }

  }

}

new CRMM_Cleanup();

/* End of file crmm-cleanup-api.php */
/* Location: ./includes/crmm-cleanup-api.php */