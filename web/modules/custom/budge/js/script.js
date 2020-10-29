(function ($, Drupal) {
  Drupal.behaviors.budgeBehavior = {
    attach: function (context, settings) {
      $('table.budge').once().each(function () {
        $(this).DataTable(
          {
            "pageLength": 50,
          }
        );
      });
    }
  };
})(jQuery, Drupal);
