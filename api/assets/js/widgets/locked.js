+function ($) {
  'use strict';

  var Locked = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)

    this.$wrapper = $(this.options.wrapper);

    this.$target = $(this.options.target)
    this.$inputs = this.$target.find('input');
    this.$select = this.$target.find('select');
    this.$btns = this.$target.find('.sonata-collection-add, .sonata-collection-delete');
    this.$btns_add = this.$target.find('.sonata-collection-add');
    this.$btns_delete = this.$target.find('.sonata-collection-delete');

    this.$popover = this.$wrapper.popover({
      'title': 'Cuidado!',
      'html': true,
      'content': this.popOverContent.bind(this)
    });

    this.run()
  }


  Locked.prototype.defaults = function () {
    return {
      target: this.$element.data('locked'),
      wrapper: this.$element.data('wrapper') || '.locked-wrapper',
      alerts: this.$element.data('alerts') || {},
      colors: this.$element.data('colors') || {
          UPDATE: '#008d4c',
          RESET: 'red'
      }
    }
  }

  Locked.prototype.run = function () {
    this.toggle(this.$element.val());

    this.$element.on('change', function () {
      this.toggle(this.$element.val())
    }.bind(this))
  }


  Locked.prototype.toggle = function (value) {

    switch (value) {
      case 'LOCKED':
        this.doLocked();
        break;
      case 'RESET':
        this.doReset()
        break;
      case 'UPDATE':
        this.doUpdate()
        break;
    }
    this.$element.prop('disabled', false)
  }

  Locked.prototype.doLocked = function () {
    this.$inputs.prop("readonly", true);
    this.$select.select2("readonly", true);
    this.$btns.addClass('disabled');

    this.$popover.popover('hide');
  }

  Locked.prototype.doReset = function () {
    this.$inputs.prop("readonly", false);
    this.$select.select2("readonly", false);
    this.$btns.removeClass('disabled');

    this.showPopOver();
  }

  Locked.prototype.doUpdate = function () {
    this.$inputs.each(function () {
      const locked = $(this).data('disabled') === 'data-disabled';
      $(this).prop('readonly', locked)
    })

    this.$select.each(function () {
      const locked = $(this).data('disabled') === 'data-disabled';
      $(this).select2('readonly', locked)
    })

    this.$btns_add.removeClass('disabled');

    this.$btns_delete.each(function () {
      const locked = $(this).data('disabled') === 'data-disabled';
      $(this).removeClass('disabled');
      if (locked) {
        $(this).addClass('disabled');
      }
    })

    this.showPopOver();
  }

  Locked.prototype.showPopOver = function () {
    const value = this.$element.val();
    this.$popover.popover('show');

    let $wrapper = this.$popover.siblings('.popover')
    let $arrow = this.$popover.siblings('.popover').find('.arrow')
    let $title = $wrapper.find('h3.popover-title')

    $wrapper.css('top', -10);
    $wrapper.css('min-height', 140);
    $arrow.css('top', '38%');

    let $bgColor = this.options.colors[value] || 'gray';

    $title.css({'background-color': $bgColor, 'color': 'white', 'font-size': '1.4em'})
  }

  Locked.prototype.popOverContent = function () {
    const value = this.$element.val();

    return this.options.alerts[value] || '(Texto sin definir)';
  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data    = $this.data('br.locked')
      if (!data) $this.data('br.locked', (data = new Locked(this, options) ))
    })
  }

  $.fn.locked = Plugin
  $.fn.locked.Constructor = Locked

  $(function () {
    $('[data-locked]').locked()
  })

}(jQuery)
