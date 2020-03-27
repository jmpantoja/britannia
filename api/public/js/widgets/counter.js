$(function () {
  $('.counter.counter-wrapper').counter()
})


$.widget('britannia.counter', {
  options: {},

  _create: function () {

    this.textarea = this.element.find('.counter-area');
    this.label = this.element.find('.counter-info');
    this.maxLength = this.textarea.attr("maxlength") ?? 500;

    this._on(this.textarea, {
      input: 'update'
    });

    this.update();
  },

  update: function () {
    var currentLength = this.textarea.val().length;
    var diff = this.maxLength - currentLength;

    this.label.removeClass('warning');
    this.label.removeClass('error');
    this.label.removeClass('success');

    if (diff <= 0) {
      this.label.addClass('error');
    } else if (diff < 50) {
      this.label.addClass('warning');
    } else {
      this.label.addClass('success');
    }

    var text = currentLength + " / " + this.maxLength;
    this.label.text(text);
  }

});
