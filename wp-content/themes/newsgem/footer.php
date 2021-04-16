<?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?>

<footer class="footer wow fadeInUp">
     <div class="container">
       <div class="row">
		 <?php dynamic_sidebar( 'footer-widgets' ); ?>
		   
       </div>
     </div>
	<p>
		Copyright &copy; 2021 <u><a style="color:red" href="https://www.w3schools.com/">W3School</a></u>
	
	
		   </p>
   </footer>
    <?php endif; ?>
   <?php do_action('newsgem_copyright');?>
    <div class="scrollup" style="bottom:0"><i class="fa fa-angle-up"></i></div>
</div><!--close-wrapper-->
<?php wp_footer();?>
</body>
</html>