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
    this.toggle(this.$element.val());

    this.$element.on('change', function () {
      this.toggle(this.$element.val())
    }.bind(this))
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


  // TOGGLE PLUGIN DEFINITION
  // ========================

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      new ToggleField(this, options)
    })
  }

  $.fn.toggleField = Plugin
  $.fn.toggleField.Constructor = ToggleField

  $(function () {
    $('[data-toggle=field]').toggleField()
  })

}(jQuery)
