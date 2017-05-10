(function($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(function() {
        //$('#rcno_reviews_settings\\[rcno_taxonomy_selection\\]\\[author\\]')
        //    .attr('disabled', 'disabled'); //This taxonomy should never be disabled by users.

        //$('#rcno_reviews_settings\\[rcno_taxonomy_selection\\]\\[genre\\]')
        //    .attr('disabled', 'disabled'); //This taxonomy should never be disabled by users.
    });

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

            var gr_ajx_gif = $('.rcno-ajax-loading');
            var gr_isbn = $('#rcno_book_isbn').val();
            var gr_key = '8bQh2W6yuSRpi9Ejs6xINw';
            // https://www.goodreads.com/book/isbn/0441172717?key=8bQh2W6yuSRpi9Ejs6xINw
            var url = 'https://www.goodreads.com/book/isbn/' + gr_isbn + '?key=' + gr_key;

            $.ajax({
                url: 'http://query.yahooapis.com/v1/public/yql',
                type: 'GET',
                data: {
                    q: "select * from xml where url=\"" + url + "\"",
                    format: 'json'
                },
                beforeSend: function() {
                    gr_ajx_gif.show();
                },
                complete: function() {
                    console.log('Complete');
                    gr_ajx_gif.hide();
                },
                success: function(grDoc) {
                    console.log(grDoc['query']['results']);
                    var book = grDoc['query']['results'];
                    //console.log(book['GoodreadsResponse']);

                    if (book['error']) {
                        console.log(book['error']);
                    } else {
                        $('#title').val(
                            book['GoodreadsResponse']['book']['title']
                        );

                        tinymce.get('rcno_book_description').setContent(
                            book['GoodreadsResponse']['book']['description']
                        );

                        $('#new-tag-rcno_author').val(
                            book['GoodreadsResponse']['book']['authors']['author']['name']
                        );

                        $('#new-tag-rcno_genre').val(
                            $.upCase(book['GoodreadsResponse']['book']['popular_shelves']['shelf'][0]['name'])
                        );

                        if( typeof book['GoodreadsResponse']['book']['series_works'] === 'object' ){
                            $('#new-tag-rcno_series').val(
                                $.sanitize( book['GoodreadsResponse']['book']['series_works']['series_work']['series']['title'] )
                            );
                        }

                        $('#rcno_book_publisher').val(
                            book['GoodreadsResponse']['book']['publisher']
                        );

                        $('#rcno_book_pub_date').val(
                            book['GoodreadsResponse']['book']['publication_month']
                            + '/' + book['GoodreadsResponse']['book']['publication_day']
                            + '/' + book['GoodreadsResponse']['book']['publication_year']
                        );

                        $('#rcno_book_pub_format').val(
                            book['GoodreadsResponse']['book']['format']
                        );

                        $('#rcno_book_page_count').val(
                            book['GoodreadsResponse']['book']['num_pages']
                        );

                        $('#rcno_book_gr_review').val(
                            book['GoodreadsResponse']['book']['average_rating']
                        );

                    }

                }
            });

        })

    });

})(jQuery);