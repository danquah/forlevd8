module.exports = function (grunt) {
  "use strict";

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: {
      options: {
        sourceMap: true,
        outputStyle: 'expanded'
      },
      dist: {
        files: [{
          expand: true,
          cwd: 'sass',
          src: ['*.scss', '**/*.scss'],
          dest: 'css',
          ext: '.css',
          extDot: 'last'
        }],
        options: {
          includePaths: require('node-bourbon').includePaths
        }
      }
    },

    clean: ["css"],

    jshint: {
      files: ['Gruntfile.js', 'js/custom/*.js']
    },

    watch: {
      // atBegin ensures that this task is run before watching starts.
      init: {
        tasks: ['build'],
        files: [],
        options: {
          atBegin: true
        }
      },
      scss: {
        files: ['sass/*.scss'],
        tasks: ['sass']
      },

      css: {
        files: ['css/*.css'],
        options: {
          livereload: true
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-clean');

  grunt.registerTask('default', ['build']);
  grunt.registerTask('build', ['clean', 'sass', 'jshint']);
};
