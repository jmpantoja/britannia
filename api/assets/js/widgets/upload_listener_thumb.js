+function ($) {
  'use strict';

  var UploadListenerThumb = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)
    this.$source = $(this.options.source);

    this.run()
  }

  UploadListenerThumb.prototype.defaults = function () {
    return {
      source: this.$element.data('source') || null,
      downloadUrl: this.$element.data('download-url') || null,
    }
  }

  UploadListenerThumb.prototype.run = function () {

    this.$source.on('uploaded', function (event, response) {
      let src = `${this.options.downloadUrl}/${response.path}`;
      this.$element.attr('src', src);

    }.bind(this))

  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.upload_listener_thumb')
      if (!data) $this.data('br.upload_listener_thumb', (data = new UploadListenerThumb(this, options)))
    })
  }

  $.fn.uploadListenerThumb = Plugin
  $.fn.uploadListenerThumb.Constructor = UploadListenerThumb

  $(function () {
    $('[data-upload-listener=thumb]').uploadListenerThumb()
  })

}(jQuery)
