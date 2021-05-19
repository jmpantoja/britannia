+function ($) {
  'use strict';

  var ShowFilters = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)
    this.$filtersPanel = $(this.options.filtersPanel);
    this.$tablePanel = $(this.options.tablePanel);
    this.$width = this.$filtersPanel.width()

    this.run()
  }


  ShowFilters.prototype.defaults = function () {
    return {
      'hasFilters': this.$element.data('has-filters') || false,
      'filtersPanel': this.$element.data('filters-panel') || '.sonata-ba-list-filters',
      'tablePanel': this.$element.data('table-panel') || '.sonata-ba-list-table'
    }
  }

  ShowFilters.prototype.run = function () {
    if (!this.options.hasFilters) {
      this.$element.iCheck('uncheck');
      this.hide();
    }

    this.$element.on('ifChecked', function () {
      this.open();
    }.bind(this));

    this.$element.on('ifUnchecked', function () {
      this.close();
    }.bind(this));

  }

  ShowFilters.prototype.hide = function () {
    this.$filtersPanel.find('.card .card-body').hide();
    this.$filtersPanel.find('.card .card-footer').hide();

    this.$filtersPanel.css({"width": '-=' + this.$width});
    this.$tablePanel.css({"width": '+=' + this.$width});
  }

  ShowFilters.prototype.open = function () {
    this.$filtersPanel.find('.card .card-body').show();
    this.$filtersPanel.find('.card .card-footer').show();

    this.$tablePanel.animate({"width": '-=' + this.$width});
    this.$filtersPanel.animate({"width": '+=' + this.$width});
  }

  ShowFilters.prototype.close = function () {
    this.$filtersPanel.find('.card .card-body').hide();
    this.$filtersPanel.find('.card .card-footer').hide();

    this.$filtersPanel.animate({"width": '-=' + this.$width});
    this.$tablePanel.animate({"width": '+=' + this.$width});
  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.show_filters')
      if (!data) $this.data('br.show_filters', (data = new ShowFilters(this, options)))
    })
  }

  $.fn.showFilters = Plugin
  $.fn.showFilters.Constructor = ShowFilters

  $(function () {
    $('[data-toggle=filters]').showFilters()
  })

}(jQuery)
