+function ($) {
  'use strict';

  var UploadListenerLink = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)
    this.$source = $(this.options.source);

    this.run()
  }

  UploadListenerLink.prototype.defaults = function () {
    return {
      source: this.$element.data('source') || null,
      downloadUrl: this.$element.data('download-url') || null,
    }
  }

  UploadListenerLink.prototype.run = function () {

    this.$source.on('uploaded', function (event, response) {
      var text = `Download (${response.size})`
      var href = `${this.options.downloadUrl}/${response.path}`;
//      '{{ path('attachment_download') }}/' + response.path

      this.$element.text(text)
      this.$element.removeClass('hidden');
      this.$element.attr('href', href);


    }.bind(this))

  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.upload_listener_link')
      if (!data) $this.data('br.upload_listener_link', (data = new UploadListenerLink(this, options)))
    })
  }

  $.fn.uploadListenerLink = Plugin
  $.fn.uploadListenerLink.Constructor = UploadListenerLink

  $(function () {
    $('[data-upload-listener=link]').uploadListenerLink()

    $('.sonata-collection-add').on('sonata-collection-item-added', function () {
      $('[data-upload-listener=link]').uploadListenerLink()

    })
  })

}(jQuery)
