<?php
/**
* @package owl-slideshow
* @version 0.0.7
*/
/*
Plugin Name: Owl Slideshow
Version: 0.0.7
Description: Output an Owl Carousel 2 slideshow using Wordpress’s built-in gallery shortcode.
Author: Nick Weaver
*/

if (!class_exists('OwlSlideshow')) {
  require_once(dirname(__FILE__) . '/lib/owl_slideshow.class.php');
}
new OwlSlideshow();
