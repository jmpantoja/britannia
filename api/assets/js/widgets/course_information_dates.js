+function ($) {
  'use strict';

  var CourseInformationDate = function (element, options) {
    this.$element = $(element)
    this.$datePicker = $('#dp_' + this.$element.attr('id'));
    this.options = $.extend({}, this.defaults(), options)

    this.$firstMonth = $(this.options.firstMonth);
    this.$firstMonthDesc = $(this.options.firstMonthDesc);
    this.$lastMonth = $(this.options.lastMonth);
    this.$lastMonthDesc = $(this.options.lastMonthDesc);

    this.run()
  }

  CourseInformationDate.prototype.defaults = function () {
    return {
      'endpoint': this.$element.data('endpoint') || null,
      'firstMonth': this.$element.data('first-month') || null,
      'firstMonthDesc': this.$element.data('first-month-desc') || null,
      'lastMonth': this.$element.data('last-month') || null,
      'lastMonthDesc': this.$element.data('last-month-desc') || null
    }
  }

  CourseInformationDate.prototype.run = function () {
    this.$datePicker.on('dp.change', this.update.bind(this));
  }


  CourseInformationDate.prototype.update = function () {

    $.ajax({
      type: 'POST',
      url: this.options.endpoint,
      data: {
        'startDate': this.$element.val()
      },
      dataType: "json",
      success: function (data) {
        this.$firstMonth.val(data.first_month_price)
        this.$firstMonthDesc.text(data.first_month_description)

        this.$lastMonth.val(data.last_month_price)
        this.$lastMonthDesc.text(data.last_month_description)
      }.bind(this)
    });

  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.course_information_date')
      if (!data) $this.data('br.course_information_date', (data = new CourseInformationDate(this, options)))
    })
  }

  $.fn.courseInformationDate = Plugin
  $.fn.courseInformationDate.Constructor = CourseInformationDate

  $(function () {
    $('[data-course-information=date]').courseInformationDate()
  })

}(jQuery)
