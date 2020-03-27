$.widget('britannia.toggle_field', {

  options: {
    select: 'select:first'
  },

  _create: function () {
    this.select = this.element.find(this.options.select);
    this.children = this.element.find(this.options.children);

    this.toggle(this.select.val());

    this.select.on('change', function (event) {
      this.toggle(event.val);
    }.bind(this))

  },

  toggle: function (value) {
    if (this.shouldBeVisible(value)) {
      this.children.show();
    } else {
      this.children.hide();
    }
  },

  shouldBeVisible: function (value) {
    var hasErrors = this.children.find('.sonata-ba-field-error').length > 0;
    var isHideValue = $.inArray(value, this.options.hide_values) >= 0

    return hasErrors || !isHideValue;
  }
});
