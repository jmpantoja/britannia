+function ($) {
  'use strict';

  var TermDate = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)

    this.$start = $(this.options.start)
    this.$end = $(this.options.end)

    this.run()
  }


  TermDate.prototype.defaults = function () {
    return {
      'url': this.$element.data('url') || null,
      'start': this.$element.data('start'),
      'end': this.$element.data('end'),
    }
  }

  TermDate.prototype.run = function () {

    this.update();
    this.$element.on('change', function () {
      this.update();
    }.bind(this))
  }

  TermDate.prototype.update = function () {

    $.ajax({
      type: 'POST',
      url: this.options.url,
      data: {
        'term': this.$element.val()
      },
      dataType: "json",
      success: function (data) {
        this.$start.val(data.start)
        this.$end.val(data.end)

        let disabled = data.disabled || [];

        disabled.forEach(function (value) {
          this.$element.find('option[value=' + value + ']').prop('disabled', true);
        }.bind(this))

      }.bind(this)
    });


  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data    = $this.data('br.term_date')
      if (!data) $this.data('br.term_date', (data = new TermDate(this, options) ))

    })
  }

  $.fn.formDate = Plugin
  $.fn.formDate.Constructor = TermDate

  $(function () {
    $('[data-term=date]').formDate()
  })

}(jQuery)
