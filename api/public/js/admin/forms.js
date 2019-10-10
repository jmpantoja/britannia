$.widget('britannia.toggle_field', {

  options: {},
  _create: function () {
    this.select = this.element.find('select');
    this.children = this.element.find(this.options.children);

    this.toggle(this.select.val());

    this.select.on('change', function (event) {
      this.toggle(event.val);
    }.bind(this))


  },

  toggle: function (value) {
    if (this.shouldBeVisible(value)) {

//      this.children.show();

    }
    else {
//      this.children.hide();

    }
  },

  shouldBeVisible: function (value) {
    return $.inArray(value, this.options.hide_values) < 0;
  }

})
