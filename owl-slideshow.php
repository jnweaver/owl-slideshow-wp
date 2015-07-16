<?php
/**
* @package owl-slideshow
* @version 0.0.1
*/
/*
Plugin Name: Owl Slideshow
Version: 0.0.1
Description: Output an Owl Carousel 2 slideshow using Wordpress’s built-in gallery shortcode.
Author: Nick Weaver
Version: 0.0.1
*/

if (!class_exists('OwlSlideshow')) {
  require_once(dirname(__FILE__) . '/lib/owl_slideshow.class.php');
}
new OwlSlideshow();

// function owl_slideshow_init() {

//   // Load library
//   include_once(dirname(__FILE__) . '/lib/owl_slideshow.class.php');

//   // init the plugin
//   new OwlSlideshow();

// }
// add_action('plugins_loaded', 'owl_slideshow_init', 0);
