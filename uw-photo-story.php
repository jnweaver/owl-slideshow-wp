<?php
/**
* @package uw-photo-story
* @version 0.0.14
*/
/*
Plugin Name: UW Photo Story
Version: 0.0.1
Description: Extend Wordpress’s built-in media gallery for embedding carousels and lightboxed photo sets using the Owl 2 and Photoswipe JS libraries.
Author: Nick Weaver
*/
if (!class_exists('Timber')) {
  die("This plugin requires Timber for Wordpress.");
} else {
  // append views dir in this plugin to Timber's view locations
  // if (is_array(Timber::$dirname)) {
  //   $views = Timber::$dirname;
  // } else {
  //   $views = array(Timber::$dirname);
  // }
  // $views[] = plugin_dir_path(__FILE__) . "views";
  // Timber::$dirname = $views;
  error_log("HWWW");
  Timber::$locations = plugin_dir_path(__FILE__) . "views";
  error_log(Timber::$locations);
  error_log(print_r(Timber::$locations, true));
}

if (!class_exists('UwPhotoStory')) {
  require_once(dirname(__FILE__) . '/lib/uw_photo_story.class.php');
}


$photo_story = new UwPhotoStory();
