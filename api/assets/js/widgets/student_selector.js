+function ($) {
  'use strict';
  var StudentSelector = function (element, options) {
    this.$element = $(element)
    this.$search = this.$element.find('input.search')
    this.$choices = this.$element.find('li')
    this.$availables = this.$element.find('li.available')
    this.$grid = this.$element.find('.course_has_students__grid')[0]
    this.$courseId = this.$element.data('course')
    this.$endpoint = this.$element.data('endpoint')

    this.run();
  }

  StudentSelector.prototype.run = function () {
    this.search()
    this.append()
  }

  StudentSelector.prototype.search = function () {
    const choices = this.$choices

    this.$search.on('input', function (event) {
      const value = $(this).val().toLowerCase();

      choices.each((index, li) => {
        const text = $(li).text();
        const visible = text.toLowerCase().indexOf(value) !== -1
        $(li).toggle(visible)
      })
    })
  }

  StudentSelector.prototype.append = function () {

    this.$availables.on('click', function (ev) {
      if ($(ev.target).hasClass('selected')) {
        return
      }

      $(ev.target).addClass('selected')
      $(ev.target).removeClass('available')

      const studentId = $(ev.target).data('id');
      const courseId = this.$courseId

      $.ajax({
        url: this.$endpoint,
        type: 'POST',
        data: {
          studentId: studentId,
          courseId: courseId,
        },
        success: function (result) {
          const cell = $(result).find('.cell-wrapper')
          $(this.$grid).prepend(cell)

          $('[data-student-cell]').studentCheck()
          
          $('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle()

        }.bind(this)
      });

    }.bind(this))
  }

  StudentSelector.prototype.urlGenerator = function (studentId) {
    const courseId = this.$courseId

    return this.$path.replace('{studentId}', studentId)
      .replace('{courseId}', courseId)
  }


  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option
      var data = $this.data('br.student_selector')
      if (!data) $this.data('br.student_selector', (data = new StudentSelector(this, options)))
    })
  }

  $.fn.studentSelector = Plugin
  $.fn.studentSelector.Constructor = StudentSelector

  $(function () {
    $('[data-widget=student_selector]').studentSelector()
  })

}(jQuery)
