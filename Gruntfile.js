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
    }
  } );

  grunt.loadNpmTasks('grunt-version');
  grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );
  grunt.registerTask( 'readme', ['wp_readme_to_markdown']);
  grunt.registerTask( 'release', ['version','wp_readme_to_markdown']);

  grunt.util.linefeed = '\n';

};
