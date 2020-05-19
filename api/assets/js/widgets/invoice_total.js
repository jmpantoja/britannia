+function ($) {
  'use strict';

  var Invoice = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, this.defaults(), options)

    this.$resumePrice = $(this.options.resumePrice);
    this.$resumeDiscount = $(this.options.resumeDiscount);
    this.$resumeTotal = $(this.options.resumeTotal);

    this.run()
  }


  Invoice.prototype.defaults = function () {
    return {
      'detail': this.$element.data('detail') || '.detail',
      'numOfUnits': this.$element.data('numOfUnits') || 'input[name$="[numOfUnits]"]',
      'price': this.$element.data('price') || 'input[name$="[price]"]',
      'discount': this.$element.data('discount') || 'input[name$="[discount]"]',
      'total': this.$element.data('total') || 'input[name$="[total]"]',
      'resumePrice': this.$element.data('resume-price') || '.resume .price .num',
      'resumeDiscount': this.$element.data('resume-discount') || '.resume .discount .num',
      'resumeTotal': this.$element.data('resume-total') || '.resume .total .num',
    }
  }

  Invoice.prototype.run = function () {
    let selector = `${this.options.detail} input`;

    $(document).on("change", selector, function (event) {
      var row = $(event.target).parents(this.options.detail);
      this.update($(row));
      this.total();
    }.bind(this));


    this.total();
  }

  Invoice.prototype.update = function (row) {
    let numOfUnits = row.find(this.options.numOfUnits).val() * 1;
    let amount = row.find(this.options.price).val() * 1.0;
    let discount = row.find(this.options.discount).val() * 1.0;
    let total = row.find(this.options.total);

    let partial = amount - (amount * (discount / 100));
    let value = numOfUnits * partial;
    total.val(value)
  }

  Invoice.prototype.total = function () {
    let price = 0;
    let total = 0;

    this.$element.find(this.options.detail).map(function (index, el) {
      let row = $(el);
      let numOfUnits = row.find(this.options.numOfUnits).val() * 1;
      let pricePerUnit = row.find(this.options.price).val() * 1;

      price += numOfUnits * pricePerUnit;
    }.bind(this))

    this.$element.find(this.options.detail).map(function (index, el) {
      let row = $(el);
      let subTotal = row.find(this.options.total).val() * 1;
      total += subTotal
    }.bind(this))

    this.$resumePrice.text(price.toFixed(2))
    this.$resumeTotal.text(total.toFixed(2))

    this.$resumeDiscount.text((total - price).toFixed(2))
  }

  Invoice.prototype.calculePrice = function (row) {
    let numOfUnits = row.find(this.options.numOfUnits).val() * 1;
    let price = row.find(this.options.price).val() * 1;

    return numOfUnits * price;
  }

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var options = typeof option == 'object' && option


      var data = $this.data('br.invoice')
      if (!data) $this.data('br.invoice', (data = new Invoice(this, options)))
    })
  }

  $.fn.invoice = Plugin
  $.fn.invoice.Constructor = Invoice


  $(function () {
    $('[data-invoice]').invoice()
  })

}(jQuery)
