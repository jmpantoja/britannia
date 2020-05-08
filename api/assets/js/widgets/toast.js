+function ($) {
  'use strict';

  $(function () {
    $('[data-toast]').each(function () {

      var type = $(this).data('toast');
      var text = $(this).text();


      let title = 'Success!';
      let bgColor = '#008d4c';
      let icon = 'check';

      if(type == 'danger'){
        title = 'Error!';
        bgColor = '#d73925';
        icon = 'times';
      }

      $.toast({
        text: `<h1><i class="fa fa-${icon}"></i> ${title}</h1><br/><h2>${text}</h2><br/>`,
        showHideTransition: 'slide',
        bgColor: bgColor,
        textColor: '#eee',
        allowToastClose: true,
        hideAfter: 5000,
        stack: 5,
        textAlign: 'left',
        position: 'top-right'
      })

    });

  })

}(jQuery)
