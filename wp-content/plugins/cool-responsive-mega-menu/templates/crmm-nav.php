<?php
	$cool_options = $posts->cool_megamenu_args;
	//echo '<pre>'; print_r($cool_options); echo '</pre>';
	$menu = wp_get_nav_menu_object( $cool_options['crmm_nav'] );    
    $menuitems = wp_get_nav_menu_items( $menu->term_id, array( 'order' => 'DESC' ) );
    $title = $posts->title;
?>
<div class="crmm-widget">
	<?php if( $title != '' ): ?>
	<h5 class="crmm-widget-title"><?php echo esc_attr($title) ?></h5>
	<?php endif; ?>
    <ul class="crmm-list">
	    <?php
	    foreach( $menuitems as $item ):
	        $link = $item->url;
	        $title = $item->title;
	        // item does not have a parent so menu_item_parent equals 0 (false)
	        if ( !$item->menu_item_parent ):
	        // save this id for later comparison with sub-menu items
	        $parent_id = $item->ID;
	        endif;
	        ?>
	        <li><a class="rose-hover" href="<?php echo esc_url($link) ?>"><?php echo esc_attr($title) ?></a></li>
	     
	    <?php endforeach; ?>
    </ul>
</div> 