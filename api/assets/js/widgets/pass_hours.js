+function ($) {
  'use strict';

  var PassHours = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)

    this.$hours =  $(this.options.hours)
    this.$lessons = this.$element.find(this.options.lessons);
    this.$target = this.$element.find(this.options.target);

    this.run()
  }

  PassHours.prototype.defaults = function () {
    return {
      'hours': this.$element.data('hours'),
      'lessons': this.$element.data('lessons') || '.pass_lesson',
      'target': this.$element.data('target') || '.left_time p.left',
      'startTime': this.$element.data('start-time') || '.start_time input',
      'endTime': this.$element.data('end-time') || '.end_time input',
    }
  }

  PassHours.prototype.run = function () {
    this.update();
    this.$hours.on('change', this.update.bind(this));

    $('[id^=dtp]').on('dp.change', this.update.bind(this));
    $('.sonata-collection-add').on('sonata-collection-item-added', function () {
      this.$lessons = this.$element.find(this.options.lessons);
      $('[id^=dtp]').on('dp.change', this.update.bind(this))
    }.bind(this));
  }

  PassHours.prototype.update = function () {
    let consumed = this.calculeConsumedTime();
    let total = this.totalTime();
    let leftTime = total - consumed;

    this.addStyle(leftTime);
    this.$target.text(this.format(leftTime));
  }

  PassHours.prototype.totalTime = function () {
    let value = this.$element.find(':selected').text();
    return parseInt(value) * 60;
  }

  PassHours.prototype.calculeConsumedTime = function () {
    let consumed = 0;

    this.$lessons.each2(function (index, el) {
      let start = $(el).find(this.options.startTime);
      let end = $(el).find(this.options.endTime);
      let startTime = moment(start.val(), 'HH:mm');
      let endTime = moment(end.val(), 'HH:mm');

      consumed += moment.duration(endTime.diff(startTime)).asMinutes();
    }.bind(this))

    return consumed;
  }

  PassHours.prototype.format = function (leftTime) {
    let hours = parseInt(leftTime / 60);
    let minutes = parseInt(leftTime % 60);

    let formatted = moment(`${hours}:${minutes}`, 'HH:mm').format('HH:mm');

    if (leftTime < 0) {
      formatted = `- ${formatted}`;
    }

    return formatted;
  }

  PassHours.prototype.addStyle = function (leftTime) {

    this.$target.removeClass('warning');
    this.$target.removeClass('danger');

    if (leftTime < 60) {
      this.$target.addClass('warning');
    }

    if (leftTime <= 0) {
      this.$target.addClass('danger');
    }
  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.pass_hours')
      if (!data) $this.data('br.pass_hours', (data = new PassHours(this, options)))
    })
  }

  $.fn.passHours = Plugin
  $.fn.passHours.Constructor = PassHours

  $(function () {
    $('[data-pass=hours]').passHours()
  })

}(jQuery)
