+function ($) {
  'use strict';

  var MonthCalendar = function (element, options) {
    this.$el = element;
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)
    this.$modal = $(this.options.modal);

    this.$calendar = this.calendar();

    this.$toggle = this.$modal.find('input[name="holiday"]');
    this.$start = this.$modal.find('input[name="start"]');
    this.$end = this.$modal.find('input[name="end"]');
    this.$submit = this.$modal.find('button.submit');

    this.run()


    $(document).on('click', this.options.prevBtn, this.putHash.bind(this))
    $(document).on('click', this.options.nextBtn, this.putHash.bind(this))
    $(document).on('click', this.options.todayBtn, this.today.bind(this))
  }

  MonthCalendar.prototype.defaults = function () {
    return {
      'endpoint': this.$element.data('endpoint'),
      'change_status_endpoint': this.$element.data('change-status-endpoint'),
      'modal': this.$element.data('modal-dialog'),
      'prevBtn': this.$element.data('prev-button'),
      'nextBtn': this.$element.data('next-button'),
      'todayBtn': this.$element.data('today-button')
    }
  }

  MonthCalendar.prototype.run = function () {
    this.$submit.on('click', this.submit.bind(this))
    this.$calendar.render();
  }

  MonthCalendar.prototype.putHash = function () {
    let date = this.$calendar.getDate();
    let hash = moment(date).format('Y-MM-DD');
    window.location.hash = hash;

    return false;
  }

  MonthCalendar.prototype.today = function () {
    let today = moment().format('Y-MM-DD');

    window.location.hash = today;
    this.$calendar.gotoDate(today);
    return false;
  }

  MonthCalendar.prototype.calendar = function () {
    return new Calendar(this.$el, {
      schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
      plugins: [interaction, dayGrid, resourceTimeGrid],
      selectable: true,
      locale: 'es',
      firstDay: 1,
      nowIndicator: false,
      now: this.current,
      header: {
        rigt: 'prev,next today',
        left: 'title',
      },
      events: this.options.endpoint,
      eventRender: this.eventRender,
      select: this.select.bind(this),

    });
  }

  MonthCalendar.prototype.current = function () {
    var hash = new URI().fragment();
    var date = moment(hash, 'Y-MM-DD', true);

    var current = null;
    if (date.isValid()) {
      current = date.format('Y-MM-DD')
    }

    return current || moment().format('Y-MM-DD');
  }

  MonthCalendar.prototype.eventRender = function (info) {
    var data = info.event.extendedProps;
    var date = info.event.start;
    var end = info.event.end;

    while (date < end) {
      var formatted = moment(date).format('YYYY-MM-DD');
      var td = $('[data-date="' + formatted + '"]');

      date.setDate(date.getDate() + 1)
      td.removeClass('holiday')

      if (data.holiday) {
        td.addClass('holiday')
      }
    }
  }

  MonthCalendar.prototype.select = function (info) {
    var start = moment(info.start).format('YYYY-MM-DD');
    var end = moment(info.end).format('YYYY-MM-DD');

    this.$toggle.bootstrapToggle('off')
    this.$start.val(start)
    this.$end.val(end)

    this.$modal.modal({
      keyboard: false,
    })
  }

  MonthCalendar.prototype.submit = function () {
    var holiday = this.$toggle.prop('checked');

    var start = this.$start.val()
    var end = this.$end.val()

    $.ajax({
      type: 'POST',
      url: this.options.change_status_endpoint,
      data: {
        'start': start,
        'end': end,
        'holiday': holiday
      },
      dataType: "json",
      success: function (data) {
        this.$calendar.refetchEvents()
      }.bind(this)
    });

  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.full-calendar')
      if (!data) $this.data('br.full-calendar', (data = new MonthCalendar(this, options)))
    })
  }

  $.fn.monthCalendar = Plugin
  $.fn.monthCalendar.Constructor = MonthCalendar

  $(function () {
    $('[data-calendar=calendar]').monthCalendar()
  })


}(jQuery)
