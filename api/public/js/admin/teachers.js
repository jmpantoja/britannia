$(function () {

  $('.cell.student-cell input.reason').on('click', function (event) {
    event.stopPropagation();
    return false;
  })

  $('.cell.student-cell.attended, .cell.student-cell.no-attended').on('click', function () {
    let _missed = $(this).find('input.missed');
    let _reason = $(this).find('input.reason');
    let _footer = $(this).find('.cell-footer');

    let isMissed = $(this).hasClass('no-attended');

    if (isMissed) {
      $(_missed).val(0);
      $(_footer).text('Asiste a clase');
      $(this).removeClass('no-attended');
      $(this).addClass('attended');
      $(_reason).addClass('hidden');

    } else {
      $(_missed).val(1);
      $(_footer).text('Falta a clase');
      $(this).removeClass('attended');
      $(this).addClass('no-attended');
      $(_reason).removeClass('hidden');
    }
  })

  $('.cell.student-cell.active, .cell.student-cell.inactive').on('click', function () {
    let _input = $(this).find('input');

    let isActive = $(this).hasClass('active');

    if (isActive) {
      $(_input).val(0);
      $(this).removeClass('active');
      $(this).addClass('inactive');
    } else {
      $(_input).val(1);
      $(this).removeClass('inactive');
      $(this).addClass('active');
    }
  })


  $('[data-toggle="popover"]').popover()
});
