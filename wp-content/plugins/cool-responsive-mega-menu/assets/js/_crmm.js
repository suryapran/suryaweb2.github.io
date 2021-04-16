  /*----------------------------------------------------*/
  /*  Mega menu script
  /*----------------------------------------------------*/
;(function($) {
  "use strict";
  
  $('.dropdown-menu').append('<span class="caret"></span>');
  function crmm_megamenu(){

    $('.crmm-megamenu').each(function(){
      var width, pos, left;

      $(this).removeClass('nl-simple');

      if ( $(this).hasClass('megamenu-containerwidth') ) {
        var container = $(this).closest('.container');
        width = container.innerWidth();
        pos = $(this).offset();
        left = pos.left - (container.offset()).left;
        
      }
      if ( $(this).hasClass('megamenu-navbarwidth') ) {
        if( $(this).closest('header').hasClass('navbar-style2') )
          var container = $(this).closest('.cool-megamenu');
        else
          var container = $(this).closest('.cool-megamenu .navbar-nav');
        width = container.innerWidth();
        pos = $(this).offset();
        left = pos.left - (container.offset()).left;
      }
      $(this).find('.dropdown-menu').css({left : - left, minWidth: width, maxWidth: width,  });
      $(this).find('.dropdown-menu .caret').css( {'left' :  left+20 } );
    })
  } 

  
  

  $(window).on( 'resize', function(){
    if ( $(window).width() > 991 ) {
      crmm_megamenu();
    }else{
      $('.crmm-megamenu').find('.dropdown-menu').css({});
    }
  })

  crmm_megamenu();
})( jQuery, window, document );  