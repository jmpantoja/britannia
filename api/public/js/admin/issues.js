;$(function () {
  $('#filter_status_value_recipient').on('change', function () {
    if ($(this).val() == 3) {
      $('#filter_status_value_status').val("0");
      $('#filter_status_value_status').select2().trigger("change");
      $('#filter_status_value_status').prop("disabled", true);
    } else {
      $('#filter_status_value_status').prop("disabled", false);
    }
  })

  $('#filter_status_value_recipient').trigger('change');
});

//
// $(function () {
//   $('[data-toggle="popover"]').popover()
// });
