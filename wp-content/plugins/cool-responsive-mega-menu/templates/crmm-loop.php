<?php
$info = $posts->info;
$title = $posts->title;
?>
<div class="crmm-widget">
	<?php if( $title != '' ): ?>
	<h5 class="crmm-widget-title"><?php echo esc_attr($title) ?></h5>
	<?php endif; ?>
	<ul class="popular-posts">
    <?php
    	$count = 1;
		while ( $posts->have_posts() ):
			$posts->the_post(); $li_class = has_post_thumbnail()? 'has-thumb' : 'no-thumb'; ?>
			<li class="clearfix d-flex align-items-center <?php echo esc_attr($li_class); ?>">
				<?php if( has_post_thumbnail() ): ?>
					<?php the_post_thumbnail( 'thumbnail', array('class' => 'img-fluid') ); ?>
				<?php endif; ?>
				<div class="post-summary">
				<a class="rose-hover" href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
				<p><?php echo get_the_date(); ?></p>				
				</div>
			</li>  
    <?php 
        $count++; 
        endwhile;        
    ?>
   </ul>
</div>