+function ($) {
  'use strict';

  var ChangerTab = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)

    this.$changerTab = $(this.options.changerTab)
    this.$changerTabActive = $(this.options.changerTabActive)
    this.$tabInput = $(this.options.tab)

    this.run()
  }


  ChangerTab.prototype.defaults = function () {
    return {
      'changerTab': this.$element.data('changer-tab') || '.changer-tab',
      'changerTabActive': this.$element.data('changer-tab-active') || 'li.active .changer-tab',
      'tab': this.$element.data('tab'),
    }
  }

  ChangerTab.prototype.run = function () {


    this.$changerTab.on('click', this.change.bind(this))

    this.$changerTabActive.trigger('click');
  }

  ChangerTab.prototype.change = function (event) {
    let href = $(event.target).attr('href');
    let pieces = href.split('_');
    let tab = pieces[pieces.length - 1];

    let others = $('[name$="[selected]"]');
    let selected = $(href).find('[name$="[selected]"]');

    $(others).val(null)
    $(selected).val('selected')

    this.$tabInput.val(tab)
  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.changer_tab')
      if (!data) $this.data('br.changer_tab', (data = new ChangerTab(this, options)))
    })
  }

  $.fn.changerTab = Plugin
  $.fn.changerTab.Constructor = ChangerTab

  $(function () {
    $('[data-changer=tab]').changerTab()
  })

}(jQuery)
