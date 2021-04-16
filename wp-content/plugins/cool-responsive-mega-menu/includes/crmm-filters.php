<?php
/**
 * Cool Megamenu functions
 */
// Cool megamenu default arguments setup
add_filter( 'cool_wp_nav_menu_args', 'cool_megamenu_nav_args', 10, 1 );  
function cool_megamenu_nav_args( $args ){



  $args['link_class'] = '';
  $args['link_attr'] = array();

  $args['li_attr'] = array();
  $args['li_before'] = '';
  $args['li_after'] = '';
  $args['fallback_cb'] = 'Cool_Megamenu_Walker::fallback';

  $args['dropdown_attr'] = array();
  $args['dropdown_before'] = '';
  $args['dropdown_after'] = '';
  $args['megamenu_before'] = '';
  $args['megamenu_after'] = '';
  $args['dropdown_inner_before'] = '';
  $args['dropdown_inner_after'] = '';
  $args['cool_megamenu'] = '';


  $args['cool_megamenu'] = ( $args['theme_location'] != '' )? apply_filters('cool_megamenu_type', crmm_get_option('crmm_megamenu_type', 'bs4')) : '';  
 
  

  return apply_filters( 'cool_megamenu_default_nav_args', $args);
}

add_filter( 'cool_megamenu_default_nav_args', 'cool_megamenu_default_nav_args_callback' );
function cool_megamenu_default_nav_args_callback($args){
	if( ($args['cool_megamenu'] == 'bs4') && ($args['theme_location'] != '') ){
		$args['container_class'] = ($args['container_class'] != '')? 'cool-megamenu collapse navbar-collapse '.$args['container_class'] : $args['container_class'];
		$args['container_id'] = ($args['container_id'] == '')? 'navbarNavDropdown' : $args['container_id'];
		$args['menu_class'] = ($args['container_class'] == '')? 'navbar-nav ml-auto'.$args['menu_class']: $args['menu_class'];   

    $args['dropdown_inner_before'] = '<div class="crrm-inner row">';
    $args['dropdown_inner_after'] = '</div>';

    $args = apply_filters( 'cool_megamenu_default_nav_args/bs4', $args);
	}

  if( ($args['cool_megamenu'] == 'custom') && ($args['theme_location'] != '') ){ 
    $args = apply_filters( 'cool_megamenu_default_nav_args/custom', $args);
  }

  
  $args['walker'] = new Cool_Megamenu_Walker();


	return $args;
}

// li class
add_filter( 'crmm_nav_menu_css_class', 'crmm_nav_menu_css_class_callback', 10, 4 );
function crmm_nav_menu_css_class_callback( $classes, $item, $args, $depth ){ 

    
    $cool_options = crmm_get_cool_options($item->ID);
    $pcool_options = crmm_get_parent_item_by_child_item($item);

    if( $args->cool_megamenu == 'bs4' ):  
        if( isset($pcool_options['second_level_settings']) &&  
          ($pcool_options['second_level_settings'] == 'on')){
            $classes[] = 'crmm-nav-item'; 
          if (($key = array_search('menu-item', $classes)) !== false) {
              unset($classes[$key]);
          }
        }else{
            $classes[] = ($depth == 0)? 'nav-item' : '';  
        }
        
         
        if ( in_array( 'menu-item-has-children', $item->classes ) ) {
            $classes[] = 'dropdown';
        }        
             
    endif; 

    if( $args->cool_megamenu == 'custom' ): 
        
    endif;
    

    if($pcool_options){ 
        if(isset($pcool_options['cool_megamenu_display']) && $pcool_options['cool_megamenu_display'] == 'on'){
            $classes[] = $cool_options['megamenu_column'];
        }        
    }   

    if($depth == 0){
      if(isset($cool_options['cool_megamenu_display']) && ($cool_options['cool_megamenu_display'] == 'on')){
        $classes[] = 'crmm-megamenu';
        $classes[] = 'megamenu-containerwidth';        
      }    	
    }

    $classes[] = 'crmm-depth-'.$depth;	

    return $classes;
}

add_filter( 'crmm_nav_menu_li_attributes', 'crmm_nav_menu_li_attributes_callback', 10, 4 );
function crmm_nav_menu_li_attributes_callback( $atts, $item, $args, $depth ){
    if( !empty($args->li_attr) ) {
        foreach ($args->li_attr as $key => $value) {
            if( !is_array($value) ) $atts[$key] = esc_attr($value);
        }
    } 
    return $atts;
}

add_filter( 'crmm_dropdown_tag', 'crmm_dropdown_tag_callback', 20, 4 );
function crmm_dropdown_tag_callback($tag, $depth, $args, $item){ 
    $cool_megamenu_display = '';
    if( ($args->cool_megamenu == 'bs4') ){     
        $tag = 'div';
        $args->dropdown_tag = $tag;
    }
    if( ($args->cool_megamenu == 'custom') && !empty($cool_options) ){  
        $cool_options = crmm_get_cool_options($item->ID);   
        extract($cool_options);
        $tag = 'ul';
        if($cool_megamenu_display == 'on'){
            $tag = 'div';
           $args->dropdown_tag = $tag;
        }

    }
    return $tag;
}
add_filter( 'crmm_dropdown_list_tag', 'crmm_dropdown_list_tag_callback', 10, 4 );
function crmm_dropdown_list_tag_callback($tag, $item, $depth, $args){
 
    if( ($args->cool_megamenu == 'bs4') && ($item->menu_item_parent) ){     
        $tag = 'div';
    }

    if( ($args->cool_megamenu == 'custom') && ($item->menu_item_parent) ){     
        $tag = 'li';
    }

    if(isset($args->dropdown_tag) && $args->dropdown_tag != '' ){
        $tag = ($args->dropdown_tag == 'div')? 'div' : 'li';
    }


    return $tag;
}

add_filter( 'crmm_nav_menu_link_attributes', 'cool_megamenu_nav_menu_link_attributes', 10, 4 );
function cool_megamenu_nav_menu_link_attributes( $atts, $item, $args, $depth ){  

    $classes = array();
    $classes[]   = ( $args->link_class != '' )? $args->link_class        :  '';

    if( $args->cool_megamenu == 'bs4' ):
        $classes[] = ($depth == 0)? 'nav-link' : 'dropdown-item';       
        if ( in_array( 'menu-item-has-children', $item->classes ) && ($depth == 0) ) {

          $classes[] = 'dropdown-toggle';  
          $args->link_attr = array(
            //'id' => 'navbarDropdown',
            'role' => 'button',
            'data-toggle' => 'dropdown',
            'aria-haspopup' => "true",
            'aria-expanded' => 'false'
          );        
        }else{
          $args->link_attr = $atts;
        }
    endif; 

    if( $args->cool_megamenu == 'custom' ):
          
        if ( in_array( 'menu-item-has-children', $item->classes ) && ($depth == 0) ) {         
          $args->link_attr = apply_filters( 'cool_megamenu_nav_menu_link_attr/custom', array());        
        }else{
          $args->link_attr = $atts;
        }
    endif; 


    $classes = array_filter( $classes );   

    $atts['class'] = join( ' ', apply_filters( 'crmm_nav_link_css_class', $classes, $item, $args, $depth ) );


    if( !empty($args->link_attr) ) {
        foreach ($args->link_attr as $key => $value) {
            if( !is_array($value) ) $atts[$key] = esc_attr($value);
        }
    }    

    return $atts;
}

add_filter( 'crmm_nav_menu_submenu_css_class', 'crmm_nav_menu_submenu_css_class', 10, 4 );
function crmm_nav_menu_submenu_css_class($classes, $args, $depth, $item){
   $cool_options = crmm_get_cool_options($item->ID);  
   

   $cool_megamenu_custom_class =  $cool_megamenu_display = '';
   if( !empty($cool_options) ) extract($cool_options);

	if( $args->cool_megamenu == 'bs4' ){
		$classes[] = 'dropdown-menu';
    if($cool_megamenu_display == 'on'){
      $classes[] = $cool_megamenu_custom_class;
    }
	}
  if( $args->cool_megamenu == 'custom' ){   
    if($cool_megamenu_display == 'on'){
      $classes = array( 'wsmegamenu', 'clearfix', 'row', $cool_megamenu_custom_class );
    }
  }
	return $classes;
}

add_filter('crmm_nav_menu_submenu_attr', 'crmm_nav_menu_submenu_attr_callback', 10, 4);
function crmm_nav_menu_submenu_attr_callback( $atts, $args, $depth, $item ){
	
	if( $args->cool_megamenu == 'bs4' ){
		 $atts = array(); 
	}
  if( $args->cool_megamenu == 'custom' ){
     $atts = array(); 
  }
	return $atts;
}

add_filter( 'crmm_dropdown_inner_before', 'crmm_dropdown_inner_before_callback', 10, 4 );
function crmm_dropdown_inner_before_callback($output, $depth, $args, $item){ 
    return $args->dropdown_inner_before;
}
add_filter( 'crmm_dropdown_inner_after', 'crmm_dropdown_inner_after_callback', 10, 3 );
function crmm_dropdown_inner_after_callback($output, $depth, $args){
    return $args->dropdown_inner_after ; 
}

add_filter( 'walker_nav_menu_start_el', 'crmm_walker_nav_menu_start_el_callback', 10, 4 );
function crmm_walker_nav_menu_start_el_callback($item_output, $item, $depth, $args){

  $cool_options = crmm_get_cool_options($item->ID);
  $pcool_options = crmm_get_parent_item_by_child_item($item);   

  if( isset($pcool_options['cool_megamenu_display']) && ($pcool_options['cool_megamenu_display'] == 'on')){
      if( $cool_options['second_level_settings'] == 'off' ) return $item_output;
      if( $cool_options['megamenu_item_type'] == '' ) return $item_output;     
      $item_output = crmm_get_megamenu_column_item($cool_options['megamenu_item_type'], $item);
  }


  return $item_output;
}

add_filter( 'crmm_settings_value_revise', 'crmm_settings_value_callback', 10, 4 );
function crmm_settings_value_callback( $field_value, $field_id, $field_class, $item ){


  
  if( ($field_id == 'second_level_settings') && ( $field_class == 'second-level-settings') ){      
      $cool_options = crmm_get_cool_options($item->ID);
      $pcool_options = crmm_get_parent_item_by_child_item($item);      
      if( isset($pcool_options['cool_megamenu_display'] ) && $pcool_options['cool_megamenu_display'] == 'off' ){
          $field_value = 'off';
      }

  }

  if( ($field_id == 'megamenu_item_type') && ( $field_class == 'second-level-settings') ){
      $cool_options = crmm_get_cool_options($item->ID);
      $pcool_options = crmm_get_parent_item_by_child_item($item);
      if( isset($pcool_options['cool_megamenu_display'] ) && $pcool_options['cool_megamenu_display'] == 'off' ){
          $field_value = '';
      }
  }

  if( ($field_id == 'megamenu_column') && ( $field_class == 'second-level-settings') ){
      $cool_options = crmm_get_cool_options($item->ID);
      $pcool_options = crmm_get_parent_item_by_child_item($item);
      if( isset($pcool_options['cool_megamenu_display'] ) && $pcool_options['cool_megamenu_display'] == 'off' ){
          $field_value = 'col-md';
      }
  }

  

  return $field_value;
}
