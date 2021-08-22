+function ($) {
  'use strict';

  var StudentList = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)

    this.$status = this.$element.data('student-cell');
    this.$input = this.$element.find(this.options.input);
    this.$note = this.$element.find(this.options.note);
    this.$label = this.$element.find(this.options.label);

    this.run()
  }


  StudentList.prototype.defaults = function () {
    return {
      'input': this.$element.data('input') || 'input.missed',
      'note': this.$element.data('note') || 'input.reason',
      'label': this.$element.data('label') || '.cell-footer',
      'textOn': this.$element.data('on') || '',
      'textOff': this.$element.data('off') || ''
    }
  }

  StudentList.prototype.run = function () {
    if (this.isDefault()) {
      return;
    }

    this.$element.addClass(this.$status);

    this.$note.on('click', function (event) {
      event.stopPropagation();
      return false;
    })

    this.$element.on('click', function (event) {
      if ($(event.target).parents('.student_cell_footer__actions').length) {
        return
      }
      this.toggle();
    }.bind(this))
  }

  StudentList.prototype.toggle = function () {

    if (this.$element.parents('.frozen').length > 0) {
      return;
    }

    if (this.isOn()) {
      this.off();
      return;
    }

    if (this.isOff()) {
      this.on();
      return;
    }
  }

  StudentList.prototype.isDefault = function () {
    return this.$status === 'default';
  }

  StudentList.prototype.isOn = function () {
    return this.$status === 'on';
  }

  StudentList.prototype.isOff = function () {
    return this.$status === 'off';
  }

  StudentList.prototype.on = function () {
    this.$input.val(0);
    this.$element.removeClass('off');
    this.$element.addClass('on');
    this.$status = 'on';
    this.$label.text(this.options.textOn);
    this.$note.addClass('hidden');
  }

  StudentList.prototype.off = function () {
    this.$input.val(1);
    this.$element.removeClass('on');
    this.$element.addClass('off');
    this.$status = 'off';
    this.$label.text(this.options.textOff);
    this.$note.removeClass('hidden');
  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.student_check')
      if (!data) $this.data('br.student_check', (data = new StudentList(this, options)))

    })
  }

  $.fn.studentCheck = Plugin
  $.fn.studentCheck.Constructor = StudentList

  $(function () {
    $('[data-student-cell]').studentCheck()
  })

}(jQuery)
