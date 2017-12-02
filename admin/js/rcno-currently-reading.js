(function ($) {

  $.ajax({
    method: 'GET',
    url: currently_reading.api.url,
    beforeSend: function (xhr) {

      xhr.setRequestHeader('X-WP-Nonce', currently_reading.api.nonce);

    }
  })
  .then(function (r) {

    if (r.hasOwnProperty('industry')) {
      $('#industry').val(r.industry);
    }

    if (r.hasOwnProperty('amount')) {
      $('#amount').val(r.amount);
    }

  });

  $('#rcno_currently_reading').on('submit', function (e) {
    e.preventDefault();

    var data = {
      amount: $('#amount').val(),
      industry: $('#industry').val()
    };

    $.ajax({
      method: 'POST',
      url: currently_reading.api.url,
      beforeSend: function (xhr) {

        xhr.setRequestHeader('X-WP-Nonce', currently_reading.api.nonce);

      },
      data: data
    })

    .then(function (r) {

      $('#feedback').html('<p>' + currently_reading.strings.saved + '</p>');

    })

    .fail(function (r) {

      var message = currently_reading.strings.error;

      if (r.hasOwnProperty('message')) {
        message = r.message;
      }

      $('#feedback').html('<p>' + message + '</p>');

    });

  });

})(jQuery);