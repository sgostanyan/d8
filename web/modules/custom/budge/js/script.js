(function ($, Drupal) {
  Drupal.behaviors.budgeBehavior = {
    attach: function (context, settings) {
      $('#myTable').DataTable();
    }
  };
})(jQuery, Drupal);
