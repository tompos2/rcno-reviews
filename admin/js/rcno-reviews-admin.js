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
                        $('#title').val(book['GoodreadsResponse']['book']['title']);

                        tinymce.get('rcno_book_description').setContent(
                            book['GoodreadsResponse']['book']['description']
                        );

                        $('#new-tag-rcno_author').val(book['GoodreadsResponse']['book']['authors']['author']['name']);

                        //$('#new-tag-rcno_genre').val(book['GoodreadsResponse']['book']['authors']['author']['name']);

                        $('#new-tag-rcno_series').val(
                            $.sanitize(book['GoodreadsResponse']['book']['series_works']['series_work']['series']['title'])
                        );
                    }
                }
            });

            /*            $.ajax({
                            url: '/wp-admin/admin-ajax.php',
                            type: 'POST',
                            data: {
                                action: 'save_post_meta',
                                review_id: my_script_vars.reviewID,
                                gr_isbn: gr_isbn
                            },
                            success: function (data) {
                                console.log(data);
                            },
                            error: function (data) {
                                console.log('Failed!: ' + data);
                            }
                        });*/

            //$('#save-post').trigger('click');

        })

    });

})(jQuery);