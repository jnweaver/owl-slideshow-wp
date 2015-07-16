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
})(jQuery);