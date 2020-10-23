(function ($, Drupal) {
  Drupal.behaviors.budgeBehavior = {
    attach: function (context, settings) {
      $('#budge').DataTable();
    }
  };
})(jQuery, Drupal);
