module.exports = function( grunt ) {

  'use strict';
  var banner = '/**\n * <%= pkg.homepage %>\n * Copyright (c) <%= grunt.template.today("yyyy") %>\n * This file is generated automatically. Do not edit.\n */\n';
  // Project configuration
  grunt.initConfig( {

    pkg: grunt.file.readJSON( 'package.json' ),

    wp_readme_to_markdown: {
      your_target: {
        files: {
          'README.md': 'readme.txt'
        }
      },
    },
    version: {
      wp_readme: {
        options: {
          prefix: 'Stable tag: '
        },
        src: ['readme.txt']
      },
      owl_slideshow_php: {
        options: {
          prefix: '[@Vv]+ersion:? '
        },
        src: ['owl-slideshow.php']
      }
    },
    cssmin: {
      options: {
        shorthandCompacting: false,
        roundingPrecision: -1
      },
      css: {
        files: {
          'css/owl-slideshow.min.css': ['css/owl.carousel.min.css', 'css/owl.theme.default.min.css', 'css/owl-slideshow.css']
        }
      }
    },
    uglify: {
      min: {
        options: {
          beautify: false,
          mangle: true
        },
        files: {
          "js/owl-slideshow.min.js": ['js/owl.carousel.min.js', 'js/load-owl-instance.js']
        }
      }
    },
    compress: {
      css: {
        options: {
          mode: 'gzip',
          level: 9
        },
        files: {
          'css/owl-slideshow.css.min.gz': [
            'css/owl-slideshow.min.css'
          ]
        }
      },
      js: {
        options: {
          mode: 'gzip',
          level: 9
        },
        files: {
          'js/owl-slideshow.min.js.gz': [
            'js/owl-slideshow.min.js'
          ]
        }
      }
    }
  } );

  grunt.loadNpmTasks('grunt-version');
  grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );
  grunt.loadNpmTasks('grunt-contrib-compress');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-uglify');

  grunt.registerTask( 'readme', ['wp_readme_to_markdown']);
  grunt.registerTask( 'release', ['version','wp_readme_to_markdown','cssmin','uglify','compress']);

  grunt.util.linefeed = '\n';

};
