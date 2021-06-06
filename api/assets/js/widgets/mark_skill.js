+function ($) {
  'use strict';

  var MarkSkill = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)

    this.$wrapper = this.$element.find(this.options.wrapper);
    this.$definition = this.$element.find(this.options.definition);

    this.$date = this.$element.find(this.options.date);
    this.$courseId = this.$element.find(this.options.courseId);
    this.$uniqId = this.$element.find(this.options.uniqId);
    this.$termName = this.$element.find(this.options.termName);
    this.$skill = this.$element.find(this.options.skill);
    this.$addBtn = this.$element.find(this.options.addBtn);
    this.$deleteBtn = this.$element.find(this.options.deleteBtn);

    this.run()
  }

  MarkSkill.prototype.defaults = function () {
    return {
      'wrapper': this.$element.data('wrapper') || ".skill-wrapper",
      'definition': this.$element.data('definition') || ".definition-wrapper",
      'date': this.$element.data('date') || null,
      'courseId': this.$element.data('course-id') || null,
      'uniqId': this.$element.data('uniq-id') || null,
      'termName': this.$element.data('term-name') || null,
      'skill': this.$element.data('skill') || null,
      'addBtn': this.$element.data('add-btn') || null,
      'deleteBtn': this.$element.data('delete-btn') || null
    }
  }

  MarkSkill.prototype.run = function () {
    this.$addBtn.on('click', this.update.bind(this));
    this.$deleteBtn.on('click', this.delete.bind(this));
  }

  MarkSkill.prototype.update = function (event) {
    let url = $(event.target).val();
    let date = this.$date.val();

    $.ajax({
      type: 'POST',
      url: url,
      data: {
        'date': date,
        'courseId': this.$courseId.val(),
        'uniqId': this.$uniqId.val(),
        'termName': this.$termName.val(),
        'skill': this.$skill.val()
      },
      dataType: "html",
      success: function (data) {
        var html = $(data).find(this.options.wrapper).html();
        this.$wrapper.html(html);

      }.bind(this)
    });
  }
  //
  // MarkSkill.prototype.date = function () {
  //   return this.$date.parent().data('DateTimePicker').getDate();
  // }

  MarkSkill.prototype.delete = function (event) {
    let value = this.$date.val()

    let question = `¿Realmente quiere borrar el examen del día ${value}?`
    let response = confirm(question);

    return response && this.update(event)
  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.mark_skill')
      if (!data) $this.data('br.mark_skill', (data = new MarkSkill(this, options)))
    })
  }

  $.fn.markSkill = Plugin
  $.fn.markSkill.Constructor = MarkSkill

  $(function () {
    $('[data-mark=skill]').markSkill();
  })

}(jQuery)
