(function($) {
  $(".owl-carousel").owlCarousel(
    {
      items: 1,
      dots: true,
      nav: true,
      loop: true,
      singleItem : true,
      navText: [
        '<i class="prev-slide fa fa-chevron-left"></i>',
        '<i class="next-slide fa fa-chevron-right"></i>'
      ]
    }
  );
  var owl_width = $(".owl-carousel").width();
  var max_height = Math.round( owl_width * .67 ); // enforce a 3x2 ratio
  $(".owl-carousel .item img").css("max-height", max_height + 0);

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
    var slideshow_nav_buttons = $(".owl-nav div"),
        slide_img_height = $(".owl-carousel .item img").height(),
        new_height;
    
    new_top = Math.round( slide_img_height / 2 ) - 17;
    slideshow_nav_buttons.css("top", new_top + "px");
    //...
  }

  // run on page load
  reposition_nav_buttons();

  // run on window resize
  $(window).resize(function () {
    waitForFinalEvent(reposition_nav_buttons, 500, "Reset slideshow nav buttons");
  });

})(jQuery);