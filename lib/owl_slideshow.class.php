<?php

/**
 * Loads Owl Carousel assets, renders Owl 2 markup and attaches an Owl 2 slideshow
 *
 * @package owl-slideshow
 **/
class OwlSlideshow
{

  function __construct()
  {
    add_image_size( 'owl-slide', 900, 600, true );
    add_action('print_media_templates', array( $this, 'extend_gallery_settings' ));
    add_action('wp_enqueue_scripts', array( $this, 'owl_js' ));
    add_action('admin_head', array( $this, 'admin_css' ));
    add_filter( 'post_gallery', array( $this, 'owl_html'),10,2 );
  }

  /**
   * Enqueue Owl assets
   *
   * @return void
   * @author 
   **/
  public function owl_js() {
    wp_enqueue_script('owl.carousel.min.js', plugins_url( '/js/owl.carousel.min.js', dirname(__FILE__) ), array('jquery'), '2.0.0-beta.3', true);
    wp_enqueue_script('load-owl-instance.js', plugins_url( '/js/load-owl-instance.js', dirname(__FILE__) ), array('owl.carousel.min.js'), '0.0.1', true);
    wp_enqueue_style( 'owl.carousel.min.css', plugins_url('/css/owl.carousel.min.css', dirname(__FILE__) ), '2.0.0-beta.3');
    wp_enqueue_style( 'owl.theme.default.min.css', plugins_url('/css/owl.theme.default.min.css', dirname(__FILE__) ), '2.0.0-beta.3');
    wp_enqueue_style( 'owl-slideshow.css', plugins_url('/css/owl-slideshow.css', dirname(__FILE__) ), '0.0.1');
    wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css' );
  }

  public function admin_css() {
    echo '<style>
      #label_slideshow_title, #input_slideshow_title { visibility: hidden; }
      .media-sidebar #label_slideshow_title.setting input[type=text] { float: left; }
    </style>';
  }

  /**
   * Override default Wordpress gallery markup
   *
   * @return string
   **/
  public function owl_html( $output = '', $atts ) {
    global $post;

    // check to make sure gallery should be output as slideshow
    // if not, return the default $output
    if ($atts['output_as_slideshow'] == "false")
      return $output;

    $out = "";

    if ( ! empty( $atts['ids'] ) ) {
      // 'ids' is explicitly ordered, unless you specify otherwise.
      if ( empty( $atts['orderby'] ) )
        $atts['orderby'] = 'post__in';
      $atts['include'] = $atts['ids'];
    }
 
    extract(shortcode_atts(array(
      'orderby' => 'menu_order ASC, ID ASC',
      'include' => '',
      'id' => $post->ID,
      'itemtag' => 'dl',
      'icontag' => 'dt',
      'captiontag' => 'dd',
      'columns' => 3,
      'size' => 'owl-slide',
      'link' => 'file',
      'owl_slideshow_title' => ''
    ), $atts));
 
    $args = array(
      'post_type' => 'attachment',
      'post_status' => 'inherit',
      'post_mime_type' => 'image',
      'orderby' => $orderby
    );
 
    if ( !empty($include) )
      $args['include'] = $include;
    else {
      $args['post_parent'] = $id;
      $args['numberposts'] = -1;
    }
 
    $images = get_posts($args);

    $out .= '<div class="owl-slideshow">';
    if (!empty($owl_slideshow_title)) {
      $out .= '<h2 class="owl-title">' . htmlspecialchars($owl_slideshow_title, ENT_QUOTES, 'UTF-8') . '</h2>';
    }
    $out .= '<div class="gallery owl-carousel">';
    foreach ( $images as $image ) {
      $out .= '<div class="item">';
      $out .= wp_get_attachment_image($image->ID, $size);
      if (!empty($image->post_excerpt)) {
        $out .= '<p class="wp-caption-text">' . $image->post_excerpt . '</p>';
      }
      $out .= '</div>';       
    }
    $out .= "</div></div>";
    return $out;
  }

  public function extend_gallery_settings(){
  ?>
  <script type="text/html" id="tmpl-extend-gallery-settings">
    <label class="setting">
      <span><?php _e('Output as Owl slideshow?'); ?></span>
      <input id="output_as_slideshow" type="checkbox" data-setting="output_as_slideshow" value="1">
    </label>
    <label class="setting" id="label_slideshow_title">
      <span><?php _e('Slideshow title (optional)'); ?></span>
    </label>
    <input  id="input_slideshow_title" type="text" value="" data-setting="owl_slideshow_title" style="float: left !important;">
  </script>

  <script>

    jQuery(document).ready(function(){

      wp.media.view.Modal.prototype.on('open', function(){
        if (jQuery("#output_as_slideshow").is(':checked')) {
          jQuery("#label_slideshow_title").css({"visibility":"visible"});
          jQuery("#input_slideshow_title").css({"visibility":"visible"});
        }
      });

      jQuery("body.wp-admin").on("change", "#output_as_slideshow", function(){
        if (jQuery(this).is(':checked')) {
          jQuery("#label_slideshow_title").css({"visibility":"visible"});
          jQuery("#input_slideshow_title").css({"visibility":"visible"});
        } else {
          jQuery("#label_slideshow_title").css({"visibility":"hidden"});
          jQuery("#input_slideshow_title").css({"visibility":"hidden"});
        }
      });

      // add shortcode attribute and its default value to the
      // gallery settings list
      _.extend(wp.media.gallery.defaults, {
        output_as_slideshow: '',
        owl_slideshow_title: ''
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
}
