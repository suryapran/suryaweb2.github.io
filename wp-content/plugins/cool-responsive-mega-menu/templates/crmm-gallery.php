<?php  $title = $posts->title; ?>
<div class="crmm-widget">
	<?php if( $title != '' ): ?>
	<h5 class="crmm-widget-title"><?php echo esc_attr($title) ?></h5>
	<?php endif; ?>
	<?php
	$ids = $posts->cool_megamenu_args;
	echo do_shortcode("[gallery columns='2' ids='{$ids}']"); 
	?>
</div>