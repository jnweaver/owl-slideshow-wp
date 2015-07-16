=== Owl Slideshow ===
Contributors: jnweaver
Tags: carousel, slideshow, owl
Requires at least: 4.0.0
Tested up to: 4.2.2
Stable tag: 0.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Output an [Owl Carousel 2](http://owlcarousel.owlgraphic.com/) slideshow using Wordpress’s built-in gallery shortcode.

== Description ==

This Wordpress plugin takes a Wordpress Gallery and adds options to display the gallery shortcode as a slideshow using Owl Carousel 2.

The plugin add two options to the standard Wordpress Gallery options:

1. An *Output as Owl slideshow?* checkbox. Check it if you want the gallery to present as an Owl slideshow. (Otherwise, the gallery will appear as a default Wordpress gallery.)
2. An optional slideshow title text input that appears if you check the Owl slideshow checkbox. The title will appear above the slideshow.

=== Owl Options ===

You can override the plugin's default Owl 2 optios and/or pass additional options via an *owl_options* attribute inside the shortcode:

```
[gallery output_as_slideshow="true" owl_slideshow_title="A slideshow title" ids="116,119,118,117,114,174" owl_options='{"startPosition": 2, "nav": false}']
```

== Changelog ==

= 0.0.1 =
* Initial release
