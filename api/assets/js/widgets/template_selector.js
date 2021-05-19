+function ($) {
  'use strict';

  var TemplateSelector = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)
    this.$target = $(this.options.target)

    this.run()
  }

  TemplateSelector.prototype.defaults = function () {
    return {
      'values': this.$element.data('values'),
      'target': this.$element.data('target')
    }
  }

  TemplateSelector.prototype.run = function () {

    this.$element.on('change', function (event) {
      let $id = $(event.target).children("option:selected").val();
      let text = this.options.values[$id];

      this.$target.val(text);

      this.$target.trumbowyg('html', text);
      this.$target.trigger('input');

    }.bind(this))

  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.template_selector')
      if (!data) $this.data('br.template_selector', (data = new TemplateSelector(this, options)))
    })
  }

  $.fn.templateSelector = Plugin
  $.fn.templateSelector.Constructor = TemplateSelector

  $(function () {
    $('[data-template=selector]').templateSelector()
  })

}(jQuery)
