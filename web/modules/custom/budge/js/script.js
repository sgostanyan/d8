(function ($, Drupal) {
  Drupal.behaviors.budgeBehavior = {
    attach: function (context, settings) {
      $('#budge').once().DataTable(
        {
          "pageLength": 50,
        }
      );
    }
  };
})(jQuery, Drupal);
