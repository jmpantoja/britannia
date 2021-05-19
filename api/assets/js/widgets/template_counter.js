+function ($) {
  'use strict';

  var TemplateCounter = function (element, options) {
    this.$element = $(element)

    this.options = $.extend({}, this.defaults(), options)
    this.$label = this.$element.parent().find(this.options.label);// $(this.options.label)
    this.$maxLength = this.$element.attr('maxlength') ?? 500

    this.run()
  }

  TemplateCounter.prototype.defaults = function () {
    return {
      'label': this.$element.data('label') || '.counter-info'
    }
  }

  TemplateCounter.prototype.run = function () {
    this.$element.on('input', this.update.bind(this))
    this.update();
  }

  TemplateCounter.prototype.update = function () {
    let currentLength = this.$element.val().length;
    let diff = this.$maxLength - currentLength;
    let text = currentLength + " / " + this.$maxLength;

    this.$label.text(text);
    this.addStyle(diff)
  }

  TemplateCounter.prototype.addStyle = function (diff) {
    this.$label.removeClass(['warning', 'error', 'success']);

    if (diff <= 0) {
      this.$label.addClass('error');
    } else if (diff < 50) {
      this.$label.addClass('warning');
    } else {
      this.$label.addClass('success');
    }
  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option
      var data = $this.data('br.form_date')
      if (!data) $this.data('br.form_date', (data = new TemplateCounter(this, options)))
    })
  }

  $.fn.templateCounter = Plugin
  $.fn.templateCounter.Constructor = TemplateCounter

  $(function () {
    $('[data-template=counter]').templateCounter()
  })

}(jQuery)
