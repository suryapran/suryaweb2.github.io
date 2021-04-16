(function($) {
  'use strict';

$(document).ready(function() {
    /*
    * Slider Script
    */
    var owl = $('.slider-version-one');
    owl.owlCarousel({
    nav: owl.children().length > 1,
    navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
    loop: owl.children().length > 1,
    autoplayTimeout:5000,
    margin: 0,
    autoplay: true,
    items:1,
    smartSpeed:450,
    responsive: {
      0: {
    	items: 1
      },
      600: {
    	items: 1
      },
      1000: {
    	items: 1
      }
    }
    });

    // Add/Remove .focus class for accessibility
    $('.navbar-default').find( 'a' ).on( 'focus blur', function() {
    	$( this ).parents( 'ul, li' ).toggleClass( 'focus' );
    } );

    /*
    Text Rotator Function
    */
    $(".demo1 .rotate").textrotator({
      animation: "fade",
      speed: 1000
    });

    /*
    Sticky Header Function
    */
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        var nav_height = $('.navbar').innerHeight();
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top - nav_height
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });

    $('body').scrollspy({
        target: '.sticky-nav',
        offset: 60
    });

    $(window).load(function(){
        $(".sticky-nav").sticky({ topSpacing: 0 });
    });

    /*
    Top Scroller Function
    */
    $(".top-scroll").hide();
    $(window).scroll(function () {
      if ($(this).scrollTop() > 500) {
    	$('.top-scroll').fadeIn();
      } else {
    	$('.top-scroll').fadeOut();
      }
    });
    $("a.top-scroll").on('click', function(event) {
      if (this.hash !== "") {
        event.preventDefault();
        var hash = this.hash;
        $('html, body').animate({
          scrollTop: $(hash).offset().top
        }, 2000, function(){
          window.location.hash = hash;
        });
      }
    });

    /*
    //wow-animated
    */
    var wow = new WOW({
      boxClass:     'wow',      // animated element css class (default is wow)
      animateClass: 'animated', // animation css class (default is animated)
      offset:       100,        // distance to the element when triggering the animation (default is 0)
      mobile: true,             // trigger animations on mobile devices (true is default)
      live: true                // consatantly check for new WOW elements on the page (true is default)
    })
    wow.init();
});

}(jQuery));