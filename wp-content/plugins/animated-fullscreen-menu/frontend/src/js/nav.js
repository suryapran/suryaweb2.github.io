/*!
  * Scripts for navigation Table and related
  *
  *
  */

(function(){

  var transition = false; /*flag to check if the transition is happening*/
  var $ = jQuery;

  
 // $("nav").not(".navbar").hide();
  var button = $('button.animatedfsmenu-navbar-toggler');


  button.on("click", function(){
  
    //navItens();
    if ( transition ){
      return false;
    }

    transition = true;


    var navbar = $(".animatedfsmenu");


      navbar.toggleClass("navbar-expand-md");

      navbar.toggleClass('d-flex');
    if ( $(".navbar-collapse").hasClass('opacity-1-trans') ){
      $(".navbar-collapse").removeClass('opacity-1-trans'); /* remove opacity on links menu */
      transition = false;
 
    } else {
      setTimeout(function (){
        $(".navbar-collapse").addClass('opacity-1-trans'); /* add opacity on links menu */
        transition = false;

        if (typeof obj === 'afs_owl_cart') { 
          afs_owl_cart();
        }


      },800);
    }

    closeAFSmenu();

  });

  $('body').on('click', '.afsmenu__close', function(e){
    e.preventDefault();
    e.stopPropagation();
    $(this).closest('li').removeClass('has-children__on');
    $(this).remove();
  
  });

  $('body').on('click', '.afsmenu > .afs-menu-item-has-children:not(.has-children__on)', function(e){
    e.stopPropagation();
    if( ! $(this).find('.afsmenu__close').length > 0 ){
      $(this).find('>a').append('<div class="afsmenu__close">x</div>');
    }

    if( ! $(this).hasClass('has-children__on') ){
        e.preventDefault();

        $(this).addClass('has-children__on');
        return;
      } 
      
      

    });

    $('body').on('click', '.animatedfsmenu__anchor .afsmenu > .afs-menu-item-has-children.has-children__on, .afsmenu > li:not(.afs-menu-item-has-children)', function(e){
      $(".animatedfsmenu").removeClass("navbar-expand-md");
      closeAFSmenu();

    });
 
    
      $(document).scroll(function() {
        if(afsmenu.autohide_scroll){
          console.log('afsmenu:autohide_scroll');
          $('.navbar-expand-md .animatedfsmenu-navbar-toggler').click();
        }
      });
    

  })();

function closeAFSmenu() {
  jQuery('.animatedfsmenu .top').toggleClass('top-animate');
  jQuery('.animatedfsmenu .bot').toggleClass('bottom-animate');
  jQuery('.animatedfsmenu .mid').toggleClass('mid-animate');
  
  if(!afsmenu.autohide_scroll){
    jQuery('body').toggleClass('afsmenu__lockscroll');
  }

}