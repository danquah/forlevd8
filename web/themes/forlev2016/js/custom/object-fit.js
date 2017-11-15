/**
 * @file
 * Trigger polyfill to support object-fit on IE.
 *
 * Polyfill from https://github.com/bfred-it/object-fit-images/.
 */

(function ($, Drupal) {
  objectFitImages();
  $('.slick').on('lazyLoaded', function(event, slick, image, imageSource){
    objectFitImages();
  });
})(jQuery, Drupal);
