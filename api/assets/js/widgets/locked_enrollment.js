+function ($) {
  'use strict';

  var LockedEnrollment = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)
    this.$target = $(this.options.target)
    this.$locked = true

    this.run()
  }


  LockedEnrollment.prototype.defaults = function () {
    return {
      target: this.$element.data('locked-enrollment')
    }
  }

  LockedEnrollment.prototype.run = function () {
    this.$element.on('click', this.toggle.bind(this))

  }

  LockedEnrollment.prototype.toggle = function () {

    if (this.$target.attr('readonly') === 'readonly') {
      this.$target.attr('readonly', false);
      this.$element.text('Bloquear')
      return;
    }

    this.$target.attr('readonly', 'readonly');
    this.$element.text('Desbloquear');
  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.locked-enrollment')
      if (!data) $this.data('br.locked-enrollment', (data = new LockedEnrollment(this, options)))
    })
  }

  $.fn.lockedEnrollment = Plugin
  $.fn.lockedEnrollment.Constructor = LockedEnrollment

  $(function () {
    $('[data-locked-enrollment]').lockedEnrollment()
  })

}(jQuery)
