(function ($, Drupal) {
  Drupal.behaviors.budgeBehavior = {
    attach: function (context, settings) {
      $('table.budge').once().each(function () {
        $(this).DataTable(
          {
            "pageLength": 50,
            "order": [[ 2, "desc" ]],
          }
        );
      });
    }
  };
})(jQuery, Drupal);
