/*
jQuery(document).ready(function() {

  jQuery('.button').click(function() {
    var randomColor = '#'+ ('000000' + Math.floor(Math.random()*16777215).toString(16)).slice(-6);
    jQuery(this).css('color', randomColor);
  });

  jQuery('.clone').click(function() {
    jQuery('#default-button').clone().appendTo('.buttons');
  });

});
*/







(function ($, Drupal) {
  Drupal.behaviors.d8global = {
    attach: function (context, settings) {

      $('.button', context).once('test').click(function () {
        var randomColor = '#' + ('000000' + Math.floor(Math.random() * 16777215).toString(16)).slice(-6);
        $(this).css('color', randomColor);
      });

      $('.clone', context).once('test').click(function () {
        $('.buttons').append('<input class="button" type="submit" value="click">');
      });

    }
  };
})(jQuery, Drupal);
