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
          src: '*.scss',
          dest: 'css',
          ext: '.css',
          extDot: 'last'
        }],
        options: {
          includePaths: require('node-neat').includePaths,
        }
      }
    },

    clean: {
      build: ["css"],
      options: {
        force: true
      }
    },

    jshint: {
      files: ['Gruntfile.js', 'js/custom/*.js']
    },

    copy: {
      files: {
        cwd: 'node_modules/object-fit-images/dist/',  // set working folder / root to copy
        src: '**.js',           // copy all files and subfolders
        dest: 'js/contrib/',    // destination folder
        expand: true           // required when using cwd
      }
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
        files: ['sass/*.scss', 'sass/**/*.scss'],
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
  grunt.loadNpmTasks('grunt-contrib-copy');


  grunt.registerTask('default', ['build']);
  grunt.registerTask('build', ['clean', 'sass', 'jshint', 'copy']);
};
