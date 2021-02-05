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

      $('.button', context).once().click(function () {
        var randomColor = '#' + ('000000' + Math.floor(Math.random() * 16777215).toString(16)).slice(-6);
        $(this).css('color', randomColor);
      });

      $('.clone', context).once().click(function () {
        Drupal.ajax({url: '/adimeo-test/ajax'}).execute();
      });

      $(document).once().ajaxComplete(function (event, xhr, settings) {
        $('.buttons').append('<input class="button" type="submit" value="click">');
      });


      /* $('.clone', context).once('test').click(function () {


         jQuery().load('/adimeo-test/ajax');

        /!* $.ajax({
           type: 'POST',
           url: '/adimeo-test/ajax',
           complete: function (data) {
             console.log('OK');
             $('.buttons').append('<input class="button" type="submit" value="click">');
           },
           dataType: 'json',
           data: 'js=1'
         });*!/


       });*/
    }
  };
})(jQuery, Drupal);
