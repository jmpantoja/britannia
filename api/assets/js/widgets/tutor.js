+function ($) {
  'use strict';

  var Tutor = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)
    this.$createBtn = $(this.options.createBtn)
    this.run()
  }

  Tutor.prototype.defaults = function () {
    return {
      createBtn: this.$element.data('create-btn') || null,
      endpoint: this.$element.data('endpoint') || null,
      uniqId: this.$element.data('uniq-id') || null,
      fieldName: this.$element.data('field-name') || null,
    }
  }

  Tutor.prototype.run = function () {
    this.$createBtn.on('click', this.create.bind(this))
    this.$element.on('change', this.change.bind(this))
  }

  Tutor.prototype.create = function () {
    this.$element.val('');
    this.$element.trigger('change');

    return false;
  }

  Tutor.prototype.change = function () {
    var data = this.$element.find(':selected').val();

    $.ajax({
      url: this.options.endpoint,
      type: 'POST',
      data: {
        id: data,
        uniqId: this.options.uniqId,
        name: this.options.fieldName
      },
      success: function (result) {
        let contentSelector = `#${this.options.fieldName}-content`;
        let wrapperSelector = `#${this.options.fieldName}-wrapper`;

        var content = $(result).find(contentSelector);
        $(wrapperSelector).html(content)
      }.bind(this)
    });
  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.tutor')
      if (!data) $this.data('br.tutor', (data = new Tutor(this, options)))
    })
  }

  $.fn.tutor = Plugin
  $.fn.tutor.Constructor = Tutor

  $(function () {
    $('[data-tutor]').tutor()
  })

}(jQuery)
