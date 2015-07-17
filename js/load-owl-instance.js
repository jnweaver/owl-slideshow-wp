(function($) {
  var default_options = {
    items: 1,
    dots: true,
    nav: true,
    loop: true,
    singleItem : true
  }
  
  var owl_options = $.extend( default_options, window.OwlSlideshow );
  
  var owl = $(".owl-slideshow-wp .owl-carousel").owlCarousel(
    owl_options
  );
  
  // http://stackoverflow.com/a/4541963
  var waitForFinalEvent = (function () {
    var timers = {};
    return function (callback, ms, uniqueId) {
      if (!uniqueId) {
        uniqueId = "Don't call this twice without a uniqueId";
      }
      if (timers[uniqueId]) {
        clearTimeout (timers[uniqueId]);
      }
      timers[uniqueId] = setTimeout(callback, ms);
    };
  })();

  var reposition_nav_buttons = function(){
    var slideshow_nav_buttons = $(".owl-nav > div"),
        slide_img_height = $(".owl-carousel .owl-item.active img").height(),
        new_top = Math.round( slide_img_height / 2 ) - 17,
        owl_width = $(".owl-carousel").width();

    if (new_top < 0) { // the image has not loaded
      var max_height = Math.round( owl_width * .67 ); // enforce a 3x2 ratio;
      new_top = Math.round( max_height / 2 ) - 17;
    }
    slideshow_nav_buttons.css("top", new_top + "px");
  }

  // run on page load
  reposition_nav_buttons();

  // run on window resize
  $(window).resize(function () {
    waitForFinalEvent(reposition_nav_buttons, 500, "Reset slideshow nav buttons");
  });

  $(document).keyup(function (e) { 
    e = e || window.event;
    var keyCode = e.keyCode || e.which;
    if (e.which == 37 && $('.owl-theme .owl-nav .owl-prev').length) {
      owl.trigger('prev.owl.carousel');
    }
    if(e.which == 39 && $('.owl-theme .owl-nav .owl-next').length) {
      owl.trigger('next.owl.carousel');
    }
  });

})(jQuery);