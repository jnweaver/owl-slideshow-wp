<?php

/**
 * UwPhotoStory handles the presentation and javascript behavior
 * of Wordpress's built-in media galleries and corresponding shortcode.
 * The plugin overrides WP default gallery shortcode markup. It also
 * adds someoptions to the media gallery settings that allow you to embed
 * the gallery either as a carousel slideshow driven by the Owl 2 JS library
 * or as a gallery grid. (The news site applies Magnificent Popup lighbox to 
 * this type of media gallery)
 *
 * @package uw-photo-story
 **/
class UwPhotoStory
{

  protected $image_ids;
  protected $atts;
  protected $post;
  protected $data; // our data object to pass to Timber::compile
  protected $is_slideshow;
  protected $is_gallery;

  function __construct() {
    add_action('print_media_templates', array( $this, 'extend_gallery_settings' ));
    add_action('admin_head', array( $this, 'admin_css' ));
    add_filter('post_gallery', array( $this, 'render'),10,2 );
    add_action('wp_enqueue_scripts', array( $this, 'owl_js' ));
  }


  /**
   * Override default Wordpress gallery shortcode
   *
   * @return string
   **/
  public function render( $output = '', $atts = [] ) {
    global $post;
    $this->post = $post;

    if ( !empty($atts) ) {
      $this->image_ids = explode(',',$atts['ids']);
      if ( !empty($atts['owl_slideshow_image_size']) ) {
        $atts['size'] = htmlspecialchars($atts['owl_slideshow_image_size']);
      }
    }
    $this->merge_default_atts($atts);

    // check to what gallery type we need to render
    if ($atts['output_as_slideshow'] == "true") {
      $this->is_slideshow = true;
      return $this->slideshow();
    } else {
      return $this->render_gallery();
    }

  }


  /**
   * Merge shortcode attrs with defaults; set data object values
   *
   * @return void
   * @author 
   **/
  function merge_default_atts($atts) {

    // merge shortcode attributes with defaults
    $this->atts = shortcode_atts(array(
      'orderby' => 'menu_order ASC, ID ASC',
      'include' => '',
      'id' => $this->post->ID,
      'size' => 'large',
      'link' => 'file',
      'owl_slideshow_title' => ''
    ), $atts);

    $atts = $this->atts;

    // build data object
    $this->data["slideshow_title"] = $atts['owl_slideshow_title'];
    $this->data["size"] = $atts['size'];
    $this->data["image_ids"] = $this->image_ids;

  }


  /**
   * Render slideshow.twig with gallery data
   *
   * @return String Rendered HTML markup
   **/
  function slideshow() {
    $atts = $this->atts;


    if (!empty($atts['owl_options'])) {
      $json = json_decode($atts['owl_options'],true);

      // filter JSON options that are strings
      array_walk_recursive($json, function(&$value) {
        if ( is_string( $value ) ) {
          $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
      });
    } 

    // append navText option and apply filters on it
    $navTextDefault = array('<i class="owl2-prev-slide"><span class="owl2-slideshow-sr-only">Previous</span></i>',
      '<i class="owl2-next-slide"><span class="owl2-slideshow-sr-only">Next</span></i>');
    $json['navText'] = apply_filters( 'owl_slideshow_nav_text', $navTextDefault );

    // allow filtering of all Owl options at this point
    $json = apply_filters( 'owl_json_options', $json );

    // write the JSON object to the footer
    add_filter('wp_footer', function() use ($json) {
      echo '<script>window.OwlSlideshow='.json_encode($json).'</script>';
    });

    return Timber::compile("slideshow.twig", $this->data);

  }


  /**
   * Render gallery.twig with gallery data
   *
   * @return String Rendered HTML markup
   **/
  function render_gallery() {
    return Timber::compile("gallery.twig", $this->data);
  }


  /**
   * Javascript to render additional media gallery options
   *
   * @return void
   **/
  public function extend_gallery_settings(){
    $img_sizes = get_intermediate_image_sizes();
  ?>
  <script type="text/html" id="tmpl-extend-gallery-settings">
    <h4 class="owl_slideshow_heading">Owl Slideshow</h3>
    <label class="setting">
      <span><?php _e('Output as Owl slideshow?'); ?></span>
      <input id="output_as_slideshow" type="checkbox" data-setting="output_as_slideshow" value="1">
    </label>
    <label class="setting owl_slideshow_option" id="label_slideshow_title">
      <span><?php _e('Slideshow title (optional)'); ?></span>
    </label>
    <input class="setting owl_slideshow_option" id="input_slideshow_title " type="text" value="" data-setting="owl_slideshow_title" style="float: left !important;">
    <label class="setting owl_slideshow_option">
      <span><?php _e('Choose image size'); ?></span>
      <select data-setting="owl_slideshow_image_size">
      <?php foreach ($img_sizes as $size) { ?>
        <option value="<?php echo $size; ?>"><?php echo $size; ?></option>
      <?php } ?>
      </select>
    </label>
  </script>

  <script>

    jQuery(document).ready(function(){

      wp.media.view.Modal.prototype.on('open', function(){
        if (jQuery("#output_as_slideshow").is(':checked')) {
          jQuery(".owl_slideshow_option").css({"visibility":"visible"});
        }
      });

      jQuery("body.wp-admin").on("change", "#output_as_slideshow", function(){
        if (jQuery(this).is(':checked')) {
          jQuery(".owl_slideshow_option").css({"visibility":"visible"});
        } else {
          jQuery(".owl_slideshow_option").css({"visibility":"hidden"});
        }
      });

      // add shortcode attribute and its default value to the
      // gallery settings list
      _.extend(wp.media.gallery.defaults, {
        output_as_slideshow: '',
        owl_slideshow_title: '',
        owl_slideshow_image_size: 'large'
      });

      // merge default gallery settings template
      wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
        template: function(view){
          return wp.media.template('gallery-settings')(view)
               + wp.media.template('extend-gallery-settings')(view);
        }
      });

    });

  </script>
  <?php
  }

  /**
   * Enqueue Owl assets
   *
   * @return void
   * @author 
   **/
  public function owl_js() {

    wp_register_script('owl-slideshow.min.js', plugins_url( '/js/owl-slideshow.min.js', dirname(__FILE__) ), array('jquery'), '0.0.9', true);
    wp_register_style( 'owl-slideshow.min.css', plugins_url('/css/owl-slideshow.min.css', dirname(__FILE__) ), '0.0.9');

    // if ( $this->is_slideshow ) {
      wp_enqueue_script('owl-slideshow.min.js');
      wp_enqueue_style('owl-slideshow.min.css');
    // }
  }

  /**
   * Writes CSS styles to admin pages
   *
   * @return void
   **/
  public function admin_css() {
    echo '<style>
      .owl_slideshow_option { visibility: hidden; }
      .media-sidebar #label_slideshow_title.setting input[type=text] { float: left; }
      .owl_slideshow_heading {padding-top: 24px; margin-bottom: 0; clear: both;}
    </style>';
  }

  protected function filter_json(&$json) {
    $json = htmlspecialchars($json, ENT_QUOTES, 'UTF-8');
  }

}
