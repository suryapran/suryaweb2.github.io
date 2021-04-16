<?php
extract($posts->cool_megamenu_args);
$menu_title = $posts->title;
?>
<div class="crmm-card-box">
	<?php if( $title != '' ): ?>
	<h5 class="crmm-widget-title title"><?php echo esc_attr($menu_title) ?></h5>
	<?php endif; ?>
	<?php if( $image != '' ): ?>   
    <div class="fluid-width-video-wrapper"><img src="<?php echo esc_url($image) ?>" alt="featured-news"></div> <!-- Image -->
	<?php endif; ?>
	<?php if( $title != '' ): ?>
    <h5 class="h5-xs">
    	<?php echo ( $link != '' )? '<a href="'.esc_url($link).'">' : ''; ?>
    	<?php echo esc_attr($title) ?>
    	<?php echo ( $link != '' )? '</a>' : ''; ?>
    </h5>
    <?php endif; ?>
    <?php if( $desc != '' ): ?>
    <p class="wsmwnutxt"><?php echo do_shortcode($desc); ?></p>
    <?php endif; ?>
</div>

								                	

								                