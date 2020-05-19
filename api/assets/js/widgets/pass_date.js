+function ($) {
  'use strict';

  var PassDate = function (element, options) {
    this.$element = $(element);
    this.options = $.extend({}, this.defaults(), options);
    this.$target = $(this.options.target)

    this.run();
  }

  PassDate.prototype.defaults = function () {
    return {
      'target': this.$element.data('target') || null,
    }
  }

  PassDate.prototype.run = function () {

    this.$element.parent().on('dp.change', this.update.bind(this))
  }

  PassDate.prototype.update = function (event) {

    let input = $(event.target).find('input');
    let value = input.val();
    let format = input.data('date-format');

    let date = moment(value, format).endOf('month');

    this.$target.val(date.format(format));

  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.pass_date')
      if (!data) $this.data('br.pass_date', (data = new PassDate(this, options)))
    })
  }

  $.fn.passDate = Plugin
  $.fn.passDate.Constructor = PassDate

  $(function () {
    $('[data-pass=date]').passDate()
  })

}(jQuery)
