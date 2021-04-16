<?php if ( ! defined( 'CRMM_VERSION' ) ) exit( 'No direct script access allowed' );


function cool_megamenu_meta_boxes() {
  
  /**
   * Create a custom meta boxes array that we pass to 
   * the Megamenu Meta Box API Class.
   */
  $my_meta_box = array(
    'id'          => 'cool_megamenu_meta_box',
    'title'       => __( 'Cool megamenu Meta Box', 'theme-text-domain' ),
    'desc'        => '',
    'pages'       => array( 'nav_menu_item' ),
    'context'     => 'normal',
    'priority'    => 'high',
    'fields'      => array(           
      array(
        'label'       => __( 'Mega menu', 'cool-megamenu' ),
        'id'          => 'cool_megamenu_display',
        'type'        => 'on-off',
        'std'         => 'off',
        'class'       => 'first-level-settings',
      ),      
      array(
        'label'       => __( 'Custom class for mega menu', 'cool-megamenu' ),
        'id'          => 'cool_megamenu_custom_class',
        'type'        => 'text',
        'std'         => '',
        'condition'   => 'cool_megamenu_display:is(on)',
        'operator'    => 'or',
        'class'       => 'first-level-settings',
      ),
      array(
        'label'       => __( 'Mega menu settings', 'cool-megamenu' ),
        'id'          => 'second_level_settings',
        'type'        => 'on-off',
        'std'         => 'off',
        'desc'        => __( 'Please make sure that Megamenu display is on in Parent item', 'cool-megamenu' ),
        'class'       => 'second-level-settings',
      ),
      array(
        'label'       => __( 'Mega menu column size', 'cool-megamenu' ),
        'id'          => 'megamenu_column',
        'type'        => 'select',
        'std'         => 'col-md',
        'choices'     => crmm_megamenu_column_classes(),
        'condition'   => 'second_level_settings:is(on)',
        'class'       => 'second-level-settings',
      ),  
      array(
        'label'       => __( 'Navigation Label display', 'cool-megamenu' ),
        'id'          => 'navigation_label',
        'type'        => 'on-off',
        'std'         => 'on',
        'condition'   => 'second_level_settings:is(on)',
        'class'       => 'second-level-settings',
      ),    
      array(
        'label'       => __( 'Select Mega menu item type', 'cool-megamenu' ),
        'id'          => 'megamenu_item_type',
        'type'        => 'select',
        'std'         => '',
        'choices'     => crmm_megamenu_item_options(),
        'condition'   => 'second_level_settings:is(on)',
        'class'       => 'second-level-settings',
      ),
      array(
          'id' => 'crmm_nav',
          'label' => __('Select Nav menu', 'cool-megamenu'),
          'desc' => '<a href="' . admin_url( 'nav-menus.php' ) . '" class="nav-link">' . __( 'Add a menu', 'cool-megamenu' ) . '</a>',
          'std' => '',
          'type' => 'select',
          'condition'   => 'megamenu_item_type:is(menu)',
          'choices' => crmm_get_terms_choices('nav_menu'),
          'class'       => 'second-level-settings',
      ),
      array(
            'id' => 'crmm_gallery',
            'label' => __('Gallery', 'cool-megamenu'),          
            'std' => '',
            'type' => 'gallery',
            'condition'   => 'megamenu_item_type:is(gallery)',
            'class'       => 'second-level-settings',
      ),
      array(
            'id' => 'crmm_recent_post',
            'label' => __('Display posts', 'cool-megamenu'),          
            'std' => '3',
            'type' => 'numeric-slider',
            'min_max_step' => '1,10,1',
            'condition'   => 'megamenu_item_type:is(recent-posts)',
            'class'       => 'second-level-settings',
      ),
      array(
            'id' => 'crmm_image',
            'label' => __('Upload Image', 'cool-megamenu'), 
            'type' => 'upload',
            'condition'   => 'megamenu_item_type:is(image)',
            'class'       => 'second-level-settings',
      ),
      array(
            'id' => 'crmm_card_image',
            'label' => __('Upload Card Image', 'cool-megamenu'), 
            'type' => 'upload',
            'condition'   => 'megamenu_item_type:is(card)',
            'class'       => 'second-level-settings',
      ),
      array(
            'id' => 'crmm_card_title',
            'label' => __('Card title', 'cool-megamenu'), 
            'type' => 'text',
            'condition'   => 'megamenu_item_type:is(card)',
            'class'       => 'second-level-settings',
      ),
      array(
            'id' => 'crmm_card_link',
            'label' => __('Card link URL', 'cool-megamenu'), 
            'type' => 'text',
            'desc' => 'Optional',
            'condition'   => 'megamenu_item_type:is(card)',
            'class'       => 'second-level-settings',            
      ),
      array(
            'id' => 'crmm_card_desc',
            'label' => __('Card description', 'cool-megamenu'), 
            'type' => 'textarea',
            'condition'   => 'megamenu_item_type:is(card)',
            'class'       => 'second-level-settings',
            'rows' => 3,
      ),
      array(
            'id' => 'crmm_shortcode',
            'label' => __('Shortcode', 'cool-megamenu'), 
            'type' => 'textarea',
            'condition'   => 'megamenu_item_type:is(shortcode)',
            'class'       => 'second-level-settings',
            'rows' => 2,
      ),
    )
  );

  $my_meta_box = apply_filters( 'cool_megamenu_meta_boxes_fields', $my_meta_box );

  
  /**
   * Register our meta boxes using the 
   * crmm_register_meta_box() function.
   */
  if ( function_exists( 'crmm_register_meta_box' ) )
    crmm_register_meta_box( $my_meta_box );

}
add_action( 'admin_init', 'cool_megamenu_meta_boxes' );


/**
 * Cool Megamenu Meta Box API
 */
if ( ! class_exists( 'CRMM_Meta_Box' ) ) {

  class CRMM_Meta_Box {
  
    /* variable to store the meta box array */
    private $meta_box;
  
    /**
     * PHP5 constructor method.
     *
     * This method adds other methods of the class to specific hooks within WordPress.
     */
    function __construct( $meta_box ) {
      if ( ! is_admin() )
        return;

      global $crmm_meta_boxes;

      if ( ! isset( $crmm_meta_boxes ) ) {
        $crmm_meta_boxes = array();
      }

      $crmm_meta_boxes[] = $meta_box;

      $this->meta_box = $meta_box;

      add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_megamenu_fields' ) );
      add_action( 'cool_megamenu_meta_boxes', array( $this, 'build_meta_box' ), 10, 1  );
      add_action( 'wp_update_nav_menu_item', array( $this, 'update_megamenu_fields'), 10, 3 );
      

    }
    
    /**
     * Adds meta box to any post type
     */
    function add_megamenu_fields($menu_item) {
      
      foreach ( $this->meta_box['fields'] as $field ) {
        $id = $field['id'];
        $menu_item->{$id} = get_post_meta( $menu_item->ID, '_menu_item_'.$id, true );
      }
      $menu_item->cool_megamenu = get_post_meta( $menu_item->ID, '_menu_item_cool_megamenu', true );
      return $menu_item;
      
    }
    
    /**
     * Meta box view
     * 
     */
    function build_meta_box( $item ) {  



      $item_id = esc_attr( $item->ID );
      $parent_id =  intval( $item->menu_item_parent);

      $pcool_options = crmm_get_parent_item_by_child_item($item); 


      echo '<div class="crmm-metabox-wrapper">';

        /* Use nonce for verification */
        echo '<input type="hidden" name="' . $this->meta_box['id'] . '_nonce" value="' . wp_create_nonce( $this->meta_box['id'] ) . '" />';
        
        /* meta box description */
        echo isset( $this->meta_box['desc'] ) && ! empty( $this->meta_box['desc'] ) ? '<div class="description" style="padding-top:10px;">' . htmlspecialchars_decode( $this->meta_box['desc'] ) . '</div>' : '';
      
        /* loop through meta box fields */
        foreach ( $this->meta_box['fields'] as $field ) {

         
          $id = $field['id'];
          $original_id = $field['id'];
          $original_class = $field['class'];
          /* get current post meta data */
          $field_value = isset($item->{$id})? $item->{$id} : '';

          
          /* set standard value */
          if ( isset( $field['std'] ) ) {  
            $field_value = crmm_filter_std_value( $field_value, $field['std'] );            
          }

          $field_value = apply_filters('crmm_settings_value_revise', $field_value, $original_id, $original_class, $item);

          $field_id = "edit-menu-item-{$item_id}-{$id}";
          $field_name = "menu-item-{$id}[{$item_id}]";

          $field_condition = isset( $field['condition'] ) ? $field['condition'] : '';
          if( $field_condition  != ''){ 
            $field_condition_arr = [];          
            $carr = explode(',', $field_condition);
            foreach ($carr as $key => $value) {
                $_carr = explode(':', trim($value));
                $field_condition_arr[] = $_carr[0].'_'.$item_id.':'.$_carr[1];
            }
            $field_condition = implode(', ', $field_condition_arr);

            //print_r($field_condition);
          }
          
         
         // echo $field_name;
          /* build the arguments array */
          $_args = array(
            'type'              => $field['type'],
            'field_id'          => $field_id,
            'field_name'        => $field_name,
            'field_value'       => $field_value,
            'field_desc'        => isset( $field['desc'] ) ? $field['desc'] : '',
            'field_std'         => isset( $field['std'] ) ? $field['std'] : '',
            'field_rows'        => isset( $field['rows'] ) && ! empty( $field['rows'] ) ? $field['rows'] : 10,
            'field_post_type'   => isset( $field['post_type'] ) && ! empty( $field['post_type'] ) ? $field['post_type'] : 'post',
            'field_taxonomy'    => isset( $field['taxonomy'] ) && ! empty( $field['taxonomy'] ) ? $field['taxonomy'] : 'category',
            'field_min_max_step'=> isset( $field['min_max_step'] ) && ! empty( $field['min_max_step'] ) ? $field['min_max_step'] : '0,100,1',
            'field_class'       => isset( $field['class'] ) ? $field['class'] : '',
            'field_condition'   => $field_condition,
            'field_operator'    => isset( $field['operator'] ) ? $field['operator'] : 'and',
            'field_choices'     => isset( $field['choices'] ) ? $field['choices'] : array(),
            'field_settings'    => isset( $field['settings'] ) && ! empty( $field['settings'] ) ? $field['settings'] : array(),
            'post_id'           => $item->ID,
            'meta'              => true
          );
          //print_r($_args);
          $conditions = '';
          
          /* setup the conditions */
          if ( isset( $field['condition'] ) && ! empty( $field['condition'] ) ) {
  
            $conditions = ' data-condition="' . $field_condition . '"';
            $conditions.= isset( $field['operator'] ) && in_array( $field['operator'], array( 'and', 'AND', 'or', 'OR' ) ) ? ' data-operator="' . $field['operator'] . '"' : '';
  
          }
          
          /* only allow simple textarea due to DOM issues with wp_editor() */
          if ( apply_filters( 'crmm_override_forced_textarea_simple', false, $field['id'] ) == false && $_args['type'] == 'textarea' )
            $_args['type'] = 'textarea-simple';

          // Build the setting CSS class
          if ( ! empty( $_args['field_class'] ) ) {
            
            $classes = explode( ' ', $_args['field_class'] );

            foreach( $classes as $key => $value ) {
            
              $classes[$key] = $value . '-wrap';
              
            }

            $class = 'format-settings ' . implode( ' ', $classes );
            
          } else {
          
            $class = 'format-settings';
            
          }
          //print_r($_args);
          /* option label */
          echo '<div id="setting_' . $field['id'] . '_'.$item_id.'" class="' . $class . '"' . $conditions . '>';
            
            echo '<div class="format-setting-wrap">';
              /* don't show title with textblocks */
              if ( $_args['type'] != 'textblock' && ! empty( $field['label'] ) ) {
                echo '<div class="format-setting-label">';
                  echo '<label for="' . $field['id'] . '" class="label">' . $field['label'] . '</label>';
                echo '</div>';
              }      
              /* get the option HTML */
              echo crmm_display_by_type( $_args );
              
            echo '</div>';
            
          echo '</div>';
          
        }

        echo '<div class="clear"></div>';
      
      echo '</div>';

    }
    
    /**
     * Saves the meta box values
     * 
     */
    function update_megamenu_fields( $menu_id, $menu_item_db_id, $args ) {
      $arr = array();
      foreach ( $this->meta_box['fields'] as $field ) {

        $id = $field['id'];
        $field_id = "_menu_item_{$id}";
        $field_name = "menu-item-{$id}";

        
       // Check if element is properly sent
        if ( isset($_REQUEST[$field_name]) && is_array( $_REQUEST[$field_name]) ) {
            $value = $_REQUEST[$field_name][$menu_item_db_id];
            $arr[$id] = $_REQUEST[$field_name][$menu_item_db_id];
            update_post_meta( $menu_item_db_id, $field_id , $value );
        }


      } //end foreach
      update_post_meta( $menu_item_db_id, '_menu_item_cool_megamenu', maybe_serialize($arr) );
  
    }
  }

}

/**
 * This method instantiates the meta box class & builds the UI.
 */
if ( ! function_exists( 'crmm_register_meta_box' ) ) {

  function crmm_register_meta_box( $args ) {
    if ( ! $args )
      return;   
      
    $crmm_meta_box = new CRMM_Meta_Box( $args );
  }

}

/* End of file crmm-meta-box-api.php */