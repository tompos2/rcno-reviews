(function($) {
'use strict';

    $(function() {
        $('.rcno-isbn-fetch').on('click', function(e) {
            e.preventDefault();

            $.sanitize = function(input) {
                var output = input
                    .replace(/<script[^>]*?>.*?<\/script>/gi, '')
                    .replace(/<[\/\!]*?[^<>]*?>/gi, '')
                    .replace(/<style[^>]*?>.*?<\/style>/gi, '')
                    .replace(/<![\s\S]*?--[ \t\n\r]*>/gi, '');
                return output;
            };

            $.upCase = function(str) {
                if (str.length) {
                    return str[0].toUpperCase() + str.slice(1).toLowerCase();
                } else {
                    return '';
                }
            };

            var gr_ajx_gif  = $('.rcno-ajax-loading');
            var book_isbn   = $('#rcno_book_isbn').val();
            var google_url  = 'https://www.googleapis.com/books/v1/volumes?q=isbn:';
            var api_url     = google_url + book_isbn;

            $.ajax({
                url: api_url,
                type: 'GET',
                beforeSend: function() {
                    gr_ajx_gif.show();
                },
                complete: function() {
                    gr_ajx_gif.hide();
                },
                success: function ( book ) {
                    console.log( book );

                    if ( book.totalItems === 0 ) {
                        $('.book-isbn-metabox-error').show();
                    } else {

                        $('#title').val(
                            book.items["0"].volumeInfo.title
                        );

                        $('#new-tag-rcno_author').val(
                            book.items["0"].volumeInfo.authors["0"]
                        );

                        $('#new-tag-rcno_genre').val(
                            book.items["0"].volumeInfo.categories["0"].replace( ' ', ', ' )
                        );

                        $('#rcno_book_publisher').val(
                            book.items["0"].volumeInfo.publisher
                        );

                    }
                },
                error: function () {

                }
            });

        });

    });


})(jQuery);