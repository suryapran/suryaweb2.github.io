<?php if ( ! defined( 'CRMM_VERSION' ) ) exit( 'No direct script access allowed' );


function crmm_toggle_icon_display(){
    $default = 'on';
    $display = crmm_get_option( 'crmm_megamenu_toggle_icon', $default );
    return ($display == 'on')? true : false; 
}

function crmm_get_toggle_icon(){
    return (crmm_toggle_icon_display())? '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button><!-- Responsive Menu Button -->' : '';
}



if(!function_exists('cool_megamenu')){
    function cool_megamenu($args = array()){
        echo crmm_get_toggle_icon();  
        $args = apply_filters('cool_wp_nav_menu_args', $args);     
        wp_nav_menu($args);
    }
}
function crmm_get_cool_options( $item_id = NULL ){

    $cool_options = get_post_meta($item_id, '_menu_item_cool_megamenu', true);
    $cool_options = maybe_unserialize($cool_options);

    return $cool_options;
}

function crmm_is_parent_item($item){
    $parent_id = $item->menu_item_parent;

    return ($parent_id)? $parent_id : false; 
}

function crmm_get_parent_item_by_child_item($item){   

    $parent_item_id = crmm_is_parent_item($item);
    if($parent_item_id){
      return crmm_get_cool_options($parent_item_id);
    }else{
      return false;
    }
    
}

function crmm_get_megamenu_column_item( $type = '', $item = NULL ){

  if( $type == '' ) return false;
  if($item == NULL) return false;
  $cool_options = crmm_get_cool_options($item->ID);
  $output = '';

  $title = ($cool_options['navigation_label'] == 'on')? $item->title : '';

  if( $type == 'menu' ){ 
    $args = array(
      'title' => $title,
      'template' => 'templates/crmm-nav.php', 
      'args' => $cool_options
    );
    $output .= crmm_item_type_template($args);    
  }

  if( $type == 'gallery' ){
    $args = array(
      'title' => $title,
      'template' => 'templates/crmm-gallery.php', 
      'args' => $cool_options['crmm_gallery']
    );
    $output .= crmm_item_type_template($args); 
  }

  if( $type == 'image' ){
    $args = array(
      'title' => $title,
      'template' => 'templates/crmm-image.php', 
      'args' => $cool_options['crmm_image']
    );
    $output .= crmm_item_type_template($args); 
  }

  if( $type == 'recent-posts' ){   
    $args = array(
      'title' => $item->title,
      'posts_per_page' => $cool_options['crmm_recent_post'],
      'ignore_sticky_posts' => 'yes' 
    );
    $output .= crmm_posts_template($args);
  }

  if( $type == 'card' ){   
    $args = array(
      'title' => $item->title,
      'template' => 'templates/crmm-card.php', 
      'args' => array(
            'image' => $cool_options['crmm_card_image'],
            'title' => $cool_options['crmm_card_title'],
            'link' => $cool_options['crmm_card_link'],
            'desc' => $cool_options['crmm_card_desc'],
        )
    );
    $output .= crmm_item_type_template($args);
  }

  if( $type == 'shortcode' ){
    $args = array(
      'title' => $title,
      'template' => 'templates/crmm-shortcode.php', 
      'args' => $cool_options['crmm_shortcode']
    );
    $output .= crmm_item_type_template($args); 
  }

  $output = apply_filters('crmm_get_item_type', $output, $type, $item);

  return $output;
}


function crmm_megamenu_column_classes(){
  $output =  array(
          array(
                'label' => 'Full width column',
                'value' => 'col-md-12',
              ),              
              array(
                'label' => '1/6 column',
                'value' => 'col-md-2',
              ),              
              array(
                'label' => '1/4 column',
                'value' => 'col-md-3',
              ),
              array(
                'label' => '1/3 column',
                'value' => 'col-md-4',
              ),
              array(
                'label' => '5/12 column',
                'value' => 'col-md-5',
              ),
              array(
                'label' => '1/2 column',
                'value' => 'col-md-6',
              ),
              array(
                'label' => '7/12 column',
                'value' => 'col-md-7',
              ),
              array(
                'label' => '2/3 column',
                'value' => 'col-md-8',
              ),
              array(
                'label' => 'Auto column',
                'value' => 'col-md',
              )
          );

  return apply_filters( 'crmm_megamenu_column_classes', $output );
}
if ( !function_exists( 'crmm_megamenu_item_options' ) ):
  function crmm_megamenu_item_options(){
    $output =  array(
                array(
                  'label' => 'Default menu item',
                  'value' => '',
                ),
                array(
                  'label' => 'Nav Menu',
                  'value' => 'menu',
                ),
                array(
                  'label' => 'Recent posts',
                  'value' => 'recent-posts',
                ),
                array(
                  'label' => 'Gallery',
                  'value' => 'gallery',
                ),
                array(
                  'label' => 'Image',
                  'value' => 'image',
                ),
                array(
                  'label' => 'Card box',
                  'value' => 'card',
                )
            );
    return apply_filters( 'crmm_megamenu_item_options', $output );
  }
endif;

if ( !function_exists( 'crmm_parse_text' ) ):
    function crmm_parse_text( $text, $args = array( ) ) {
        if ( is_array( $args ) ) {
            extract( shortcode_atts( array(
                 'tag' => 'span',
                'tagclass' => '',
                'before' => '',
                'after' => '' 
            ), $args ) );
        } //is_array( $args )
        else {
            extract( shortcode_atts( array(
                 'tag' => $args,
                'tagclass' => 'color-text',
                'before' => '',
                'after' => '' 
            ), $args ) );
        }

        $text = esc_attr($text);
        
        preg_match_all( "/\{([^\}]*)\}/", $text, $matches );

        if ( !empty( $matches ) ) {
            foreach ( $matches[ 1 ] as $value ) {
                $find    = "{{$value}}";
                $replace = "{$before}<{$tag} class='{$tagclass}'>{$value}</{$tag}>{$after}";
                $text    = str_replace( $find, $replace, $text );
            } //$matches[1] as $value
        } //!empty( $matches )
        return  $text;
    }
endif;

if ( !function_exists( 'crmm_get_terms_choices' ) ):
    function crmm_get_terms_choices( $tax = 'category', $key = 'slug' ) {
        $terms = array( );
        if ( !taxonomy_exists( $tax ) )
            return false;
        if ( $key === 'id' )
            foreach ( (array) get_terms( $tax, array( 'hide_empty' => false ) ) as $term )
                $terms[] = array(
                    'label' => $term->name,
                    'value' => $term->term_id
                );
        elseif ( $key === 'slug' )
            foreach ( (array) get_terms( $tax, array( 'hide_empty' => false ) ) as $term )
                $terms[] = array(
                    'label' => $term->name,
                    'value' => $term->slug
                );

        return $terms;
    }
endif;

if ( !function_exists( 'crmm_posts_template' ) ):
    function crmm_posts_template( $atts, $content = null, $type = "posts" ) {
        // Prepare error var
        $error  = null;
        // Parse attributes
        $atts  = shortcode_atts( array(
          'title' => '',
            'template' => 'templates/crmm-loop.php',
            'id' => false,
            'posts_per_page' => get_option( 'posts_per_page' ),
            'post_type' => 'post',
            'taxonomy' => 'category',
            'tax_term' => false,
            'tax_operator' => 'IN',
            'author' => '',
            'tag' => '',
            'meta_key' => '',
            'offset' => 0,
            'order' => 'DESC',
            'orderby' => 'date',
            'post_parent' => false,
            'post_status' => 'publish',
            'ignore_sticky_posts' => 'no',
            'info' => '' 
        ), $atts, $type );
        $original_atts       = $atts;
        $author              = sanitize_text_field( $atts[ 'author' ] );
        $id                  = $atts[ 'id' ]; // Sanitized later as an array of integers
        $ignore_sticky_posts = ( bool ) ( $atts[ 'ignore_sticky_posts' ] === 'yes' ) ? true : false;
        $meta_key            = sanitize_text_field( $atts[ 'meta_key' ] );
        $offset              = intval( $atts[ 'offset' ] );
        $order               = sanitize_key( $atts[ 'order' ] );
        $orderby             = sanitize_key( $atts[ 'orderby' ] );
        $post_parent         = $atts[ 'post_parent' ];
        $post_status         = $atts[ 'post_status' ];
        $post_type           = sanitize_text_field( $atts[ 'post_type' ] );
        $posts_per_page      = intval( $atts[ 'posts_per_page' ] );
        $tag                 = sanitize_text_field( $atts[ 'tag' ] );
        $tax_operator        = $atts[ 'tax_operator' ];
        $tax_term            = sanitize_text_field( $atts[ 'tax_term' ] );
        $taxonomy            = sanitize_key( $atts[ 'taxonomy' ] );
        // Set up initial query for post
        $args                = array(
             'category_name' => '',
            'order' => $order,
            'orderby' => $orderby,
            'post_type' => explode( ',', $post_type ),
            'posts_per_page' => $posts_per_page,
            'tag' => $tag 
        );
        // Ignore Sticky Posts
        if ( $ignore_sticky_posts )
            $args[ 'ignore_sticky_posts' ] = true;
        // Meta key (for ordering)
        if ( !empty( $meta_key ) )
            $args[ 'meta_key' ] = $meta_key;
        // If Post IDs
        if ( $id ) {
            $posts_in           = array_map( 'intval', explode( ',', $id ) );
            $args[ 'post__in' ] = $posts_in;
        } //$id
        // Post Author
        if ( !empty( $author ) )
            $args[ 'author' ] = $author;
        // Offset
        if ( !empty( $offset ) )
            $args[ 'offset' ] = $offset;
        // Post Status
        $post_status = explode( ', ', $post_status );
        $validated   = array( );
        $available   = array(
             'publish',
            'pending',
            'draft',
            'auto-draft',
            'future',
            'private',
            'inherit',
            'trash',
            'any' 
        );
        foreach ( $post_status as $unvalidated ) {
            if ( in_array( $unvalidated, $available ) )
                $validated[ ] = $unvalidated;
        } //$post_status as $unvalidated
        if ( !empty( $validated ) )
            $args[ 'post_status' ] = $validated;
        // If taxonomy attributes, create a taxonomy query
        if ( !empty( $taxonomy ) && !empty( $tax_term ) ) {
            // Term string to array
            $tax_term = explode( ',', $tax_term );
            // Validate operator
            if ( !in_array( $tax_operator, array(
                 'IN',
                'NOT IN',
                'AND' 
            ) ) )
                $tax_operator = 'IN';
            $tax_args         = array(
                 'tax_query' => array(
                     array(
                         'taxonomy' => $taxonomy,
                        'field' => ( is_numeric( $tax_term[ 0 ] ) ) ? 'id' : 'slug',
                        'terms' => $tax_term,
                        'operator' => $tax_operator 
                    ) 
                ) 
            );
            // Check for multiple taxonomy queries
            $count            = 2;
            $more_tax_queries = false;
            while ( isset( $original_atts[ 'taxonomy_' . $count ] ) && !empty( $original_atts[ 'taxonomy_' . $count ] ) && isset( $original_atts[ 'tax_' . $count . '_term' ] ) && !empty( $original_atts[ 'tax_' . $count . '_term' ] ) ) {
                // Sanitize values
                $more_tax_queries           = true;
                $taxonomy                   = sanitize_key( $original_atts[ 'taxonomy_' . $count ] );
                $terms                      = explode( ', ', sanitize_text_field( $original_atts[ 'tax_' . $count . '_term' ] ) );
                $tax_operator               = isset( $original_atts[ 'tax_' . $count . '_operator' ] ) ? $original_atts[ 'tax_' . $count . '_operator' ] : 'IN';
                $tax_operator               = in_array( $tax_operator, array(
                     'IN',
                    'NOT IN',
                    'AND' 
                ) ) ? $tax_operator : 'IN';
                $tax_args[ 'tax_query' ][ ] = array(
                     'taxonomy' => $taxonomy,
                    'field' => 'slug',
                    'terms' => $terms,
                    'operator' => $tax_operator 
                );
                $count++;
            } //isset( $original_atts['taxonomy_' . $count] ) && !empty( $original_atts['taxonomy_' . $count] ) && isset( $original_atts['tax_' . $count . '_term'] ) && !empty( $original_atts['tax_' . $count . '_term'] )
            if ( $more_tax_queries ):
                $tax_relation = 'AND';
                if ( isset( $original_atts[ 'tax_relation' ] ) && in_array( $original_atts[ 'tax_relation' ], array(
                     'AND',
                    'OR' 
                ) ) )
                    $tax_relation = $original_atts[ 'tax_relation' ];
                $args[ 'tax_query' ][ 'relation' ] = $tax_relation;
            endif;
            $args = array_merge( $args, $tax_args );
        } //!empty( $taxonomy ) && !empty( $tax_term )
        // Fix for pagination
        if ( is_front_page() ) {
            $paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
        } //is_front_page()
        else {
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        }
        $args[ 'paged' ] = $paged;
        // If post parent attribute, set up parent
        if ( $post_parent ) {
            if ( 'current' == $post_parent ) {
                global $post;
                $post_parent = $post->ID;
            } //'current' == $post_parent
            $args[ 'post_parent' ] = intval( $post_parent );
        } //$post_parent
        // Save original posts
        global $posts;
        $original_posts = $posts;
        // Query posts
        $posts          = new WP_Query( $args );
        $posts->info    = $atts;
        $posts->title    = $atts['title'];
        // Buffer output
        ob_start();
        // Search for template in stylesheet directory
        if ( file_exists( get_stylesheet_directory() . '/cool-megamenu/' . $atts[ 'template' ] ) )
            load_template( get_stylesheet_directory() . '/cool-megamenu/' . $atts[ 'template' ], false );
        // Search for template in theme directory
        elseif ( file_exists( get_template_directory() . '/cool-megamenu/' . $atts[ 'template' ] ) )
            load_template( get_template_directory() . '/cool-megamenu/' . $atts[ 'template' ], false );
         // Search for template in theme directory
        elseif ( file_exists( CRMM_DIR . '/' . $atts[ 'template' ] ) )
            load_template( CRMM_DIR . '/' . $atts[ 'template' ], false );  
        // Template not found
        else
            echo esc_attr(__( 'template not found', 'genemy' ));
        $output = ob_get_contents();
        ob_end_clean();
        // Return original posts
        $posts = $original_posts;
        // Reset the query
        wp_reset_postdata();
        return $output;
    }
endif;

if ( !function_exists( 'crmm_item_type_template' ) ):
    function crmm_item_type_template( $atts, $content = null ) {
        // Prepare error var
        $error = null;
        // Parse attributes
        $atts  = shortcode_atts( array(
          'title' => '',
            'template' => '',
            'args' => '' 
        ), $atts );

        if( $atts['template'] == '' ) return false;

        // Save original posts
        global $posts;
        $original_posts = $posts;
        // Query posts
        $args = array();
        $posts          = new WP_Query( $args );
        $posts->cool_megamenu_args    = $atts['args'];
        $posts->title    = $atts['title'];
        // Buffer output
        ob_start();
        // Search for template in stylesheet directory
        if ( file_exists( get_stylesheet_directory() . '/cool-megamenu/' . $atts[ 'template' ] ) )
            load_template( get_stylesheet_directory() . '/cool-megamenu/' . $atts[ 'template' ], false );
        // Search for template in theme directory
        elseif ( file_exists( get_template_directory() . '/cool-megamenu/' . $atts[ 'template' ] ) )
            load_template( get_template_directory() . '/cool-megamenu/' . $atts[ 'template' ], false );
         // Search for template in theme directory
        elseif ( file_exists( CRMM_DIR . '/' . $atts[ 'template' ] ) )
            load_template( CRMM_DIR . '/' . $atts[ 'template' ], false );  
        // Template not found
        else
            echo esc_attr(__( 'template not found', 'genemy' ));
        $output = ob_get_contents();
        ob_end_clean();
        // Return original posts
        $posts = $original_posts;
        // Reset the query
        wp_reset_postdata();
        return $output;
    }
endif;

function crmm_get_the_term_list( $id, $taxonomy, $before = '', $sep = '', $after = '', $name = true ) {
    $terms = get_the_terms( $id, $taxonomy );
    if ( is_wp_error( $terms ) )
        return $terms;
    if ( empty( $terms ) )
        return false;
    $links = array( );
    foreach ( $terms as $term ) {
        $link = get_term_link( $term, $taxonomy );
        if ( is_wp_error( $link ) ) {
            return $link;
        } //is_wp_error( $link )
        $links[ ] = ( $name ) ? $term->name : $term->slug;
    } //$terms as $term
    /**    
    * Filters the term links for a given taxonomy.    
    *    
    * The dynamic portion of the filter name, `$taxonomy`, refers    
    * to the taxonomy slug.    
    *
    * @since 2.5.0    
    *    
    * @param array $links An array of term links.    
    */
    $term_links = apply_filters( "term_links-$taxonomy", $links );
    return $before . join( $sep, $term_links ) . $after;
}
function crmm_header_search_icon( $align = "" ) {
    return '<li class="search-box' . ( ( $align != '' ) ? ' ' . $align : '' ) . '"><a href="#"><i class="fa fa-search"></i></a><ul><li><div class="search-form">' . get_search_form( false ) . '</div></li></ul></li>';
}
function crmm_post_thumbnail( $size = 'thumbnail' ) {
    global $post;
    $postid = $post->ID;
    echo crmm_get_post_thumbnail( $postid, $size );
}
function crmm_get_post_thumbnail( $postid, $size = 'thumbnail' ) {
    $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'full' );
    $sizearr         = crmm_get_image_size( $size );
    return '<img src="' . crmm_image_resize( $large_image_url[ 0 ], $sizearr[ 'width' ], $sizearr[ 'height' ] ) . '" alt="' . esc_attr(get_the_title( $postid )) . '">';
}
/**

* Get size information for all currently-registered image sizes.

*

* @global $_wp_additional_image_sizes

* @uses   get_intermediate_image_sizes()

* @return array $sizes Data for all currently-registered image sizes.

*/
function crmm_get_image_sizes( ) {
    global $_wp_additional_image_sizes;
    $sizes = array( );
    foreach ( get_intermediate_image_sizes() as $_size ) {
        if ( in_array( $_size, array(
             'thumbnail',
            'medium',
            'medium_large',
            'large',
            'full' 
        ) ) ) {
            $sizes[ $_size ][ 'width' ]  = get_option( "{$_size}_size_w" );
            $sizes[ $_size ][ 'height' ] = get_option( "{$_size}_size_h" );
            $sizes[ $_size ][ 'crop' ]   = (bool) get_option( "{$_size}_crop" );
        } //in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) )
        elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
            $sizes[ $_size ] = array(
                 'width' => $_wp_additional_image_sizes[ $_size ][ 'width' ],
                'height' => $_wp_additional_image_sizes[ $_size ][ 'height' ],
                'crop' => $_wp_additional_image_sizes[ $_size ][ 'crop' ] 
            );
        } //isset( $_wp_additional_image_sizes[ $_size ] )
    } //get_intermediate_image_sizes() as $_size
    return $sizes;
}
function crmm_get_image_sizes_Arr( ) {
    $sizes = crmm_get_image_sizes();
    $arr   = array( );
    foreach ( $sizes as $key => $value ) {
        $arr[ $key ] = $key . ' - ' . $value[ 'width' ] . 'X' . $value[ 'height' ] . ' - ' . ( ( $value[ 'crop' ] ) ? 'Cropped' : '' );
    } //$sizes as $key => $value
    return $arr;
}
/**

* Filter callback to add image sizes to Media Uploader

*/
function crmm_display_image_size_names_muploader( $sizes ) {
    $new_sizes   = array( );
    $added_sizes = get_intermediate_image_sizes();
    // $added_sizes is an indexed array, therefore need to convert it
    // to associative array, using $value for $key and $value
    foreach ( $added_sizes as $key => $value ) {
        $new_sizes[ $value ] = $value;
    } //$added_sizes as $key => $value
    // This preserves the labels in $sizes, and merges the two arrays
    $new_sizes = array_merge( $new_sizes, $sizes );
    return $new_sizes;
}
add_filter( 'image_size_names_choose', 'crmm_display_image_size_names_muploader', 11, 1 );
/**

* Get size information for a specific image size.

*

* @uses   crmm_get_image_sizes()

* @param  string $size The image size for which to retrieve data.

* @return bool|array $size Size data about an image size or false if the size doesn't exist.

*/
function crmm_get_image_size( $size ) {
    $sizes = crmm_get_image_sizes();
    if ( isset( $sizes[ $size ] ) ) {
        return $sizes[ $size ];
    } //isset( $sizes[ $size ] )
    return false;
}
/**
 * Cool Megamenu Options ID
 *
 * @return    string
 */
if ( ! function_exists( 'crmm_options_id' ) ) {

  function crmm_options_id() {
    
    return apply_filters( 'crmm_options_id', 'cool_megamenu' );
    
  }
  
}

/**
 * Theme Settings ID
 *
 * @return    string
 *
 * @access    public
 * @since     2.3.0
 */
if ( ! function_exists( 'crmm_settings_id' ) ) {

  function crmm_settings_id() {
    
    return apply_filters( 'crmm_settings_id', 'cool_megamenu_settings' );
    
  }
  
}

/**
 * Theme Layouts ID
 *
 * @return    string
 *
 * @access    public
 * @since     2.3.0
 */
if ( ! function_exists( 'crmm_layouts_id' ) ) {

  function crmm_layouts_id() {
    
    return apply_filters( 'crmm_layouts_id', 'cool_megamenu_layouts' );
    
  }
  
}

/**
 * Get Option.
 *
 * Helper function to return the option value.
 * If no value has been saved, it returns $default.
 *
 * @param     string    The option ID.
 * @param     string    The default option value.
 * @return    mixed
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'crmm_get_option' ) ) {

  function crmm_get_option( $option_id, $default = '' ) {
    
    /* get the saved options */ 
    $options = get_option( crmm_options_id() );

    
    
    /* look for the saved value */
    if ( isset( $options[$option_id] ) && '' != $options[$option_id] ) {
        
      return crmm_wpml_filter( $options, $option_id );
      
    }
    
    return $default;
    
  }
  
}

/**
 * Echo Option.
 *
 * Helper function to echo the option value.
 * If no value has been saved, it echos $default.
 *
 * @param     string    The option ID.
 * @param     string    The default option value.
 * @return    mixed
 *
 * @access    public
 * @since     2.2.0
 */
if ( ! function_exists( 'crmm_echo_option' ) ) {
  
  function crmm_echo_option( $option_id, $default = '' ) {
    
    echo crmm_get_option( $option_id, $default );
  
  }
  
}

/**
 * Filter the return values through WPML
 *
 * @param     array     $options The current options    
 * @param     string    $option_id The option ID
 * @return    mixed
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'crmm_wpml_filter' ) ) {

  function crmm_wpml_filter( $options, $option_id ) {
      
    // Return translated strings using WMPL
    if ( function_exists('icl_t') ) {
      
      $settings = get_option( crmm_settings_id() );
      
      if ( isset( $settings['settings'] ) ) {
      
        foreach( $settings['settings'] as $setting ) {
          
          // List Item & Slider
          if ( $option_id == $setting['id'] && in_array( $setting['type'], array( 'list-item', 'slider' ) ) ) {
          
            foreach( $options[$option_id] as $key => $value ) {
          
              foreach( $value as $ckey => $cvalue ) {
                
                $id = $option_id . '_' . $ckey . '_' . $key;
                $_string = icl_t( 'Cool Megamenu Options', $id, $cvalue );
                
                if ( ! empty( $_string ) ) {
                
                  $options[$option_id][$key][$ckey] = $_string;
                  
                }
                
              }
            
            }
          
          // List Item & Slider
          } else if ( $option_id == $setting['id'] && $setting['type'] == 'social-links' ) {
          
            foreach( $options[$option_id] as $key => $value ) {
          
              foreach( $value as $ckey => $cvalue ) {
                
                $id = $option_id . '_' . $ckey . '_' . $key;
                $_string = icl_t( 'Cool Megamenu Options', $id, $cvalue );
                
                if ( ! empty( $_string ) ) {
                
                  $options[$option_id][$key][$ckey] = $_string;
                  
                }
                
              }
            
            }
          
          // All other acceptable option types
          } else if ( $option_id == $setting['id'] && in_array( $setting['type'], apply_filters( 'crmm_wpml_option_types', array( 'text', 'textarea', 'textarea-simple' ) ) ) ) {
          
            $_string = icl_t( 'Cool Megamenu Options', $option_id, $options[$option_id] );
            
            if ( ! empty( $_string ) ) {
            
              $options[$option_id] = $_string;
              
            }
            
          }
          
        }
      
      }
    
    }
    
    return $options[$option_id];
  
  }

}

/**
 * Enqueue the dynamic CSS.
 *
 * @return    void
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'crmm_load_dynamic_css' ) ) {

  function crmm_load_dynamic_css() {
    
    /* don't load in the admin */
    if ( is_admin() ) {
      return;
    }

    /**
     * Filter whether or not to enqueue a `dynamic.css` file at the theme level.
     *
     * By filtering this to `false` Cool Megamenu will not attempt to enqueue any CSS files.
     *
     * Example: add_filter( 'crmm_load_dynamic_css', '__return_false' );
     *
     * @since 2.5.5
     *
     * @param bool $load_dynamic_css Default is `true`.
     * @return bool
     */
    if ( false === (bool) apply_filters( 'crmm_load_dynamic_css', true ) ) {
      return;
    }

    /* grab a copy of the paths */
    $crmm_css_file_paths = get_option( 'crmm_css_file_paths', array() );
    if ( is_multisite() ) {
      $crmm_css_file_paths = get_blog_option( get_current_blog_id(), 'crmm_css_file_paths', $crmm_css_file_paths );
    }

    if ( ! empty( $crmm_css_file_paths ) ) {
      
      $last_css = '';
      
      /* loop through paths */
      foreach( $crmm_css_file_paths as $key => $path ) {
        
        if ( '' != $path && file_exists( $path ) ) {
        
          $parts = explode( '/wp-content', $path );

          if ( isset( $parts[1] ) ) {

            $sub_parts = explode( '/', $parts[1] );

            if ( isset( $sub_parts[1] ) && isset( $sub_parts[2] ) ) {
              if ( $sub_parts[1] == 'themes' && $sub_parts[2] != get_stylesheet() ) {
                continue;
              }
            }
            
            $css = set_url_scheme( WP_CONTENT_URL ) . $parts[1];
            
            if ( $last_css !== $css ) {
              
              /* enqueue filtered file */
              wp_enqueue_style( 'crmm-dynamic-' . $key, $css, false, CRMM_VERSION );
              
              $last_css = $css;
              
            }
            
          }
      
        }
        
      }
    
    }
    
  }
  
}

/**
 * Enqueue the Google Fonts CSS.
 *
 * @return    void
 *
 * @access    public
 * @since     2.5.0
 */
if ( ! function_exists( 'crmm_load_google_fonts_css' ) ) {

  function crmm_load_google_fonts_css() {

    /* don't load in the admin */
    if ( is_admin() )
      return;

    $crmm_google_fonts      = get_theme_mod( 'crmm_google_fonts', array() );
    $crmm_set_google_fonts  = get_theme_mod( 'crmm_set_google_fonts', array() );
    $families             = array();
    $subsets              = array();
    $append               = '';

    if ( ! empty( $crmm_set_google_fonts ) ) {

      foreach( $crmm_set_google_fonts as $id => $fonts ) {

        foreach( $fonts as $font ) {

          // Can't find the font, bail!
          if ( ! isset( $crmm_google_fonts[$font['family']]['family'] ) ) {
            continue;
          }

          // Set variants & subsets
          if ( ! empty( $font['variants'] ) && is_array( $font['variants'] ) ) {

            // Variants string
            $variants = ':' . implode( ',', $font['variants'] );

            // Add subsets to array
            if ( ! empty( $font['subsets'] ) && is_array( $font['subsets'] ) ) {
              foreach( $font['subsets'] as $subset ) {
                $subsets[] = $subset;
              }
            }

          }

          // Add family & variants to array
          if ( isset( $variants ) ) {
            $families[] = str_replace( ' ', '+', $crmm_google_fonts[$font['family']]['family'] ) . $variants;
          }

        }

      }

    }

    if ( ! empty( $families ) ) {

      $families = array_unique( $families );

      // Append all subsets to the path, unless the only subset is latin.
      if ( ! empty( $subsets ) ) {
        $subsets = implode( ',', array_unique( $subsets ) );
        if ( $subsets != 'latin' ) {
          $append = '&subset=' . $subsets;
        }
      }

      wp_enqueue_style( 'crmm-google-fonts', esc_url( '//fonts.googleapis.com/css?family=' . implode( '%7C', $families ) ) . $append, false, null );
    }

  }

}

/**
 * Registers the Theme Option page link for the admin bar.
 *
 * @return    void
 *
 * @access    public
 * 
 */
if ( ! function_exists( 'crmm_register_options_admin_bar_menu' ) ) {

  function crmm_register_options_admin_bar_menu( $wp_admin_bar ) {
    
    if ( ! current_user_can( apply_filters( 'crmm_options_capability', 'edit_theme_options' ) ) || ! is_admin_bar_showing() )
      return;
    
    $wp_admin_bar->add_node( array(
      'parent'  => 'appearance',
      'id'      => apply_filters( 'crmm_options_menu_slug', 'crmm-options' ),
      'title'   => apply_filters( 'crmm_options_page_title', __( 'Cool Megamenu Options', 'cool-megamenu' ) ),
      'href'    => admin_url( apply_filters( 'crmm_options_parent_slug', 'themes.php' ) . '?page=' . apply_filters( 'crmm_options_menu_slug', 'crmm-options' ) )
    ) );
    
  }
  
}

function crmm_nav_menu_args( $args ){

}

/* End of file crmm-functions.php */
