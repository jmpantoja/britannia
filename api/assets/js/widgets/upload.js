+function ($) {
  'use strict';

  var Upload = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)
    this.$path = $(this.options.path);

    this.init()
    this.run()
  }


  Upload.prototype.defaults = function () {
    return {
      type: this.$element.data('upload') || 'image',
      path: this.$element.data('path') || null,
      uploadUrl: this.$element.data('upload-url') || null
    }
  }

  Upload.prototype.init = function () {
    if (this.options.type === 'image') {
      this.$type = 'image/jpeg';
      this.$maxsize = 2048000;
      this.$invalidSizeError = 'Tamaño de archivo incorrecto, use un archivo de 2Mb como máximo';
      this.$invalidTypeError = 'Tipo de archivo incorrecto, solo se admiten imagenes jpg';
    }

    if (this.options.type === 'pdf') {
      this.$type = 'application/pdf';
      this.$maxsize = 2048000;
      this.$invalidSizeError = 'Tamaño de archivo incorrecto, use un archivo de 2Mb como máximo';
      this.$invalidTypeError = 'Tipo de archivo incorrecto, solo se admiten documentos pdf';
    }

  }

  Upload.prototype.run = function () {

    this.$element.on('change', function (event) {

      let file = $(event.target)[0].files[0];
      let formData = this.formData(file);

      if (null === formData) {
        return;
      }

      this.upload(formData);

    }.bind(this))
  }

  Upload.prototype.formData = function (file) {

    if (!this.isValidType(file) || !this.isValidSize(file)) {
      return null;
    }

    let formData = new FormData();
    formData.append('file', file);

    return formData;
  }

  Upload.prototype.isValidSize = function (file) {

    if (file.size > this.$maxsize) {
      alert(this.$invalidSizeError);
      return false;
    }
    return true;
  }

  Upload.prototype.isValidType = function (file) {
    if (file.type !== this.$type) {
      alert(this.$invalidTypeError);
      return false;
    }
    return true
  }

  Upload.prototype.upload = function (formData) {
    $.ajax({
      url: this.options.uploadUrl,
      type: 'post',
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (response.success) {
          this.$element.val('');
          this.$path.val(response.path)

          this.$element.trigger('uploaded', response)
        } else {

          alert("Error al subir el archivo: " + response.message);
        }
      }.bind(this),
      error: function (error) {
        alert('error al subir el archivo');
      }
    });
  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option

      var data = $this.data('br.upload')
      if (!data) $this.data('br.upload', (data = new Upload(this, options)))
    })
  }

  $.fn.upload = Plugin
  $.fn.upload.Constructor = Upload

  $(function () {
    $('[data-upload]').upload()

    $('.sonata-collection-add').on('sonata-collection-item-added', function () {
      $('[data-upload]').upload();
    })
  })
}(jQuery)


