+function ($) {
  'use strict';

  var ToggleField = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)
    this.$target = $(this.options.target)

    this.run()
  }


  ToggleField.prototype.defaults = function () {
    return {
      values: this.$element.data('hide-values') || [''],
      target: this.$element.data('target') || null,
    }
  }

  ToggleField.prototype.run = function () {
    this.toggle(this.value());

    this.$element.on('change', function () {
      this.toggle(this.value())
    }.bind(this))
  }

  ToggleField.prototype.value = function () {
    if(this.$element.is('input[type=checkbox]')){
      return this.$element.is(':checked') ? "1" : "0";
    }

    return this.$element.val();
  }

  ToggleField.prototype.toggle = function (value) {
    if (this.shouldBeVisible(value)) {
      this.$target.show();
    } else {
      this.$target.hide();
    }
  }

  ToggleField.prototype.shouldBeVisible = function (value) {
    var isHideValue = $.inArray(value, this.options.values) >= 0
    return !isHideValue;
  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data    = $this.data('br.toggle_field')
      if (!data) $this.data('br.toggle_field', (data = new ToggleField(this, options) ))
    })
  }

  $.fn.toggleField = Plugin
  $.fn.toggleField.Constructor = ToggleField

  $(function () {
    $('[data-toggle=field]').toggleField()
  })

}(jQuery)
