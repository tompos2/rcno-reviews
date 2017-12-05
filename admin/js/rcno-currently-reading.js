(function ($) {

  /*$.ajax({
    method: 'GET',
    url: currently_reading.api.url,
    beforeSend: function (xhr) {

      xhr.setRequestHeader('X-WP-Nonce', currently_reading.api.nonce);

    }
  })
  .then(function (r) {

    var result = r[ r.length - 1 ];

    if (result.hasOwnProperty('industry')) {
      $('#industry').val(result.industry);
    }

    if (result.hasOwnProperty('amount')) {
      $('#amount').val(result.amount);
    }

  });*/

  $('#rcno_currently_reading').on('submit', function (e) {
    e.preventDefault();

    var finished = 0;
    if ($('#rcno_currently_reading_finished').is(":checked")) {
      finished = 1;
    }

    var cover = $('#rcno_currently_reading_upload_field').val();
    if (cover === undefined) {
      cover = $('#rcno_currently_reading_cover').attr('src');
    }

    var data = {
      book_cover: cover,
      book_title: $('#rcno_currently_reading_book_title').val(),
      book_author: $('#rcno_currently_reading_book_author').val(),
      current_page: $('#rcno_current_page_number').val(),
      num_of_pages: $('#rcno_current_num_pages').val(),
      progress_comment: $('#rcno_currently_reading_book_comment').val(),
      last_updated: JSON.parse(JSON.stringify(new Date())),
      finished_book: finished
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

      // @see https://stackoverflow.com/questions/3357553/how-do-i-store-an-array-in-localstorage
      localStorage.setItem( 'currently_reading_progress', JSON.stringify( data ) );

    })

    .fail(function (r) {

      var message = currently_reading.strings.error;

      if (r.status !== 200) {
        message = r.responseJSON.message;
      }

      $('#feedback').html('<p>' + message + '</p>');

    });

  });


  // Currently reading book cover button.
  $('.rcno_currently_upload_button').on('click', function (e) {
    e.preventDefault();

    var cover_uploader = wp.media({
      title: 'Currently Reading Book Cover',
      button: {
        text: 'Use File'
      },
      multiple: false  // Set this to true to allow multiple files to be selected
    })
      .on('select', function () {
        var attachment = cover_uploader.state().get('selection').first().toJSON();
        $('#rcno_currently_reading_upload_field').val(attachment.url);

      })
      .open();
  });

})(jQuery);
