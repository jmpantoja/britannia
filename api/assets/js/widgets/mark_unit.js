+function ($) {
  'use strict';

  var MarkUnit = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)

    this.$wrapper = this.$element.find(this.options.wrapper);
    this.$definition = this.$element.find(this.options.definition);

    this.$courseId = this.$definition.find(this.options.courseId);
    this.$uniqId = this.$definition.find(this.options.uniqId);
    this.$numOfUnits = this.$definition.find(this.options.numOfUnits);
    this.$termName = this.$definition.find(this.options.termName);
    this.$unitsWeight = this.$definition.find(this.options.unitsWeight);

    this.run()
  }

  MarkUnit.prototype.defaults = function () {
    return {
      'wrapper': this.$element.data('wrapper') || ".marks-wrapper",
      'definition': this.$element.data('definition') || ".definition-wrapper",
      'courseId': this.$element.data('course-id') || null,
      'uniqId': this.$element.data('uniq-id') || null,
      'numOfUnits': this.$element.data('num-of-units') || null,
      'termName': this.$element.data('term-name') || null,
      'unitsWeight': this.$element.data('units-weight') || null,
      'endpoint': this.$element.data('endpoint')
    }
  }

  MarkUnit.prototype.run = function () {
    this.$numOfUnits.on('change', this.update.bind(this));
    this.$unitsWeight.on('change', this.update.bind(this));
  }

  MarkUnit.prototype.update = function () {

    $.ajax({
      type: 'POST',
      url: this.options.endpoint,
      data: {
        'courseId': this.$courseId.val(),
        'uniqId': this.$uniqId.val(),
        'numOfUnits': this.$numOfUnits.val(),
        'termName': this.$termName.val(),
        'unitsWeight': this.$unitsWeight.val()
      },
      dataType: "html",
      success: function (data) {
        var html = $(data).find(this.options.wrapper).html();
        this.$wrapper.html(html);
      }.bind(this)
    });
  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.mark_unit')
      if (!data) $this.data('br.mark_unit', (data = new MarkUnit(this, options)))
    })
  }

  $.fn.markUnit = Plugin
  $.fn.markUnit.Constructor = MarkUnit

  $(function () {
    $('[data-mark=unit]').markUnit();
  })

}(jQuery)
