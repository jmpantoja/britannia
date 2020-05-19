+function ($) {
  'use strict';

  var MarkWeight = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)

    this.$exam = $(this.options.exam)
    this.run()
  }

  MarkWeight.prototype.defaults = function () {
    return {
      exam: this.$element.data('exam') || null,
    }
  }

  MarkWeight.prototype.run = function () {
    this.$element.on('keyup', function (event) {
      this.update()
    }.bind(this))

    this.update()
  }

  MarkWeight.prototype.update = function () {
    let value = this.$element.val();
    let exam = '-';
    if ($.isNumeric(value) && value >= 0 && value <= 100) {
      exam = 100 - value;
    }
    this.$exam.val(exam);
  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.mark_weight')
      if (!data) $this.data('br.mark_weight', (data = new MarkWeight(this, options)))
    })
  }

  $.fn.markWeight = Plugin
  $.fn.markWeight.Constructor = MarkWeight

  $(function () {
    $('[data-mark=weight]').markWeight();
  })

}(jQuery)
