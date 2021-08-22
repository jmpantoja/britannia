+function ($) {
  'use strict';

  var Planning = function (element, options) {
    this.$el = element;
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)
    this.$calendar = this.calendar();
    this.run()


    $(document).on('click', this.options.prevBtn, this.putHash.bind(this))
    $(document).on('click', this.options.nextBtn, this.putHash.bind(this))
    $(document).on('click', this.options.todayBtn, this.today.bind(this))
  }

  Planning.prototype.defaults = function () {
    return {
      'resources': this.$element.data('resources'),
      'endpoint': this.$element.data('endpoint'),
      'prevBtn': this.$element.data('prev-button'),
      'nextBtn': this.$element.data('next-button'),
      'todayBtn': this.$element.data('today-button')
    }
  }

  Planning.prototype.run = function () {
    this.$calendar.render();
  }

  Planning.prototype.putHash = function () {
    let date = this.$calendar.getDate();
    let hash = moment(date).format('Y-MM-DD');
    window.location.hash = hash;

    return false;
  }

  Planning.prototype.today = function () {
    let today = moment().format('Y-MM-DD');

    window.location.hash = today;
    this.$calendar.gotoDate(today);
    return false;
  }

  Planning.prototype.calendar = function () {

    return new Calendar(this.$el, {
      schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
      plugins: [interaction, dayGrid, resourceTimeGrid],
      locale: 'es',
      timeZone: 'local',
      titleFormat: {year: 'numeric', month: 'long', day: 'numeric', weekday: 'long'},
      nowIndicator: true,
      now: this.current(),
      defaultView: 'resourceTimeGridDay',
      minTime: '09:00:00',
      maxTime: '23:00:00',
      allDaySlot: false,
      slotDuration: '00:15:00',
      slotLabelFormat: {
        hour: 'numeric',
        minute: '2-digit',
        omitZeroMinute: false,
        meridiem: 'short'
      },
      resources: this.options.resources,
      events: this.options.endpoint,
      eventRender: this.eventRender.bind(this)
    });
  }

  Planning.prototype.current = function () {
    var hash = new URI().fragment();
    var date = moment(hash, 'Y-MM-DD', true);

    var current = null;
    if (date.isValid()) {
      current = date.format('Y-MM-DD')
    }

    return current || moment().format('Y-MM-DD');
  }

  Planning.prototype.eventRender = function (info) {

    console.log(info.event.start)
    console.log(info.event.end)
    console.log(info.el)
    //return info
    // var data = info.event.extendedProps;
    // var attendances = $('<div class="attendance_sumary"><br/></div>');
    //
    // $.each(data.attendances, (index, attendance) => {
    //   attendances.append(`<span class=${attendance.status} title="${attendance.student}"></span>`);
    // });
    //
    // $(info.el).find('.fc-time').html(data.schedule);
    // $(info.el).find('.fc-title').html(`<p>${info.event.title}</p>`);
    // $(info.el).find('.fc-title').append(attendances);

  }

  Planning.prototype.select = function (info) {
    var start = moment(info.start).format('YYYY-MM-DD');
    var end = moment(info.end).format('YYYY-MM-DD');

    this.$toggle.bootstrapToggle('off')
    this.$start.val(start)
    this.$end.val(end)

    this.$modal.modal({
      keyboard: false,
    })
  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.full-calendar')
      if (!data) $this.data('br.full-calendar', (data = new Planning(this, options)))
    })
  }

  $.fn.planning = Plugin
  $.fn.planning.Constructor = Planning

  $(function () {
    $('[data-calendar=planning]').planning()
  })


}(jQuery)
