+function ($) {
  'use strict';

  var FormDate = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)

    this.$btnGoTo = $(this.options.btnGoTo)
    this.$btnToday = $(this.options.btnToday)
    this.$form = this.$element.parents('form')

    this.run()
  }


  FormDate.prototype.defaults = function () {
    return {
      'btnGoTo': this.$element.data('goto-btn') || '#change-date',
      'btnToday': this.$element.data('goto-btn') || '#today',
      'format': this.$element.data('date-format') || 'D MMM YYYY'
    }
  }

  FormDate.prototype.run = function () {
    this.updateActionUrl();

    this.$btnGoTo.on('click', function () {
      let date = this.date();
      this.goTo(date)
    }.bind(this))

    this.$btnToday.on('click', function () {
      let date = moment();
      this.goTo(date)
    }.bind(this))

  }


  FormDate.prototype.updateActionUrl = function () {
    if (!URI().hasQuery('date')) {
      return;
    }

    let action = this.$form.attr('action');
    let uri = URI(action);

    let query = URI().search(true);
    let date = query.date;

    uri.search(function (query) {
      query.date = date;
    })

    this.$form.attr('action', uri.toString());
  }

  FormDate.prototype.goTo = function (date) {
    let uri = URI();
    let formatted = date.format('Y-MM-DD');

    uri.search(function (data) {
      data.date = formatted;
    });
    window.location = uri.toString()
  }


  FormDate.prototype.date = function () {
    let value = this.$element.val();
    let format = this.options.format;

    return moment(value, format)
  }


  // TOGGLE PLUGIN DEFINITION
  // ========================
  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      new FormDate(this, options)
    })
  }

  $.fn.formDate = Plugin
  $.fn.formDate.Constructor = FormDate

  $(function () {
    $('[data-form=date]').formDate()
  })

}(jQuery)
