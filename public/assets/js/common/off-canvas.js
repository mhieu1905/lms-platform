(function($) {
  'use strict';
  $(function() {
    $('[data-toggle="offcanvas"], [data-toggle="minimize"]').on("click", function() {
      $('body').toggleClass('sidebar-icon-only');
    });
  });
})(jQuery);

(function($) {
  'use strict';
    $(function() {
    $('[data-toggle="offcanvas"]').on("click", function() {
      $('.sidebar-offcanvas').toggleClass('active')
    });
  });
})(jQuery);
