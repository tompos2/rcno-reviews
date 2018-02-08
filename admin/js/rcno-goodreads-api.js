(function ($) {
    'use strict';

    $(function () {

        $('.rcno-isbn-fetch.good-reads').on('click', function (e) {
            e.preventDefault();

            $.sanitize = function (input) {
                return input
                    .replace(/<script[^>]*?>.*?<\/script>/gi, '')
                    .replace(/<[\/\!]*?[^<>]*?>/gi, '')
                    .replace(/<style[^>]*?>.*?<\/style>/gi, '')
                    .replace(/<![\s\S]*?--[ \t\n\r]*>/gi, '')
                    .trim();

            };

            $.upCase = function (str) {
                if (str.length) {
                    return str[0].toUpperCase() + str.slice(1).toLowerCase();
                } else {
                    return '';
                }
            };

            var review_title = $('#title');

            var title = $('#rcno_book_title');
            var author = $('#new-tag-rcno_author');
            var genre = $('#new-tag-rcno_genre');
            var publisher = $('#new-tag-rcno_publisher');
            var pub_date = $('#rcno_book_pub_date');
            var pub_fmt = $('#rcno_book_pub_format');
            var pub_edt = $('#rcno_book_pub_edition');
            var series  = $('#new-tag-rcno_series');
            var p_count  = $('#rcno_book_page_count');
            var s_number  = $('#rcno_book_series_number');
            var gr_rvw  = $('#rcno_book_gr_review');
            var gr_id  = $('#rcno_book_gr_id');
            var isbn13  = $('#rcno_book_isbn13');
            var asin  = $('#rcno_book_asin');
            var gr_url  = $('#rcno_book_gr_url');


            var ajx_gif = $('.rcno-ajax-loading');
            var book_isbn = $('#rcno_book_isbn').val().trim();
            var api_key = gr_options.api_key;
            // https://www.goodreads.com/book/isbn/0441172717?key=################
            var url = 'https://www.goodreads.com/book/isbn/' + book_isbn + '?key=' + api_key;

            $.ajax({
                url: 'https://query.yahooapis.com/v1/public/yql',
                type: 'GET',
                data: {
                    q: "select * from xml where url=\"" + url + "\"",
                    format: 'json'
                },
                beforeSend: function () {
                    ajx_gif.show();
                },
                complete: function () {
                    ajx_gif.hide();
                },
                success: function (grDoc) {
                    console.log(grDoc.query.results); //@TODO: Disable in production.
                    var book = grDoc.query.results;

                    if (book.error) {

                        $('.book-isbn-metabox-error').show();

                    } else {

                        review_title.val(
                            book.GoodreadsResponse.book.title
                        );

                        title.val(
                            book.GoodreadsResponse.book.work.original_title
                        );

                        tinymce.get('rcno_book_description').setContent(
                            book.GoodreadsResponse.book.description
                        );

                        author.val(
                            book.GoodreadsResponse.book.authors.author.name
                        );

                        genre.val(
                            $.upCase(book.GoodreadsResponse.book.popular_shelves.shelf["0"].name)
                        );

                        if (typeof book.GoodreadsResponse.book.series_works === 'object') {
                            if ( $.isArray( book.GoodreadsResponse.book.series_works.series_work ) ) {
                                series.val(
                                    $.sanitize( book.GoodreadsResponse.book.series_works.series_work["0"].series.title )
                                );
                            } else {
                                series.val(
                                    $.sanitize(book.GoodreadsResponse.book.series_works.series_work.series.title)
                                );
                            }
                        } else {
                            series.val('');
                        }

                        publisher.val(
                            book.GoodreadsResponse.book.publisher
                        );

                        var year  = parseInt(book.GoodreadsResponse.book.publication_year);
                        var month = parseInt(book.GoodreadsResponse.book.publication_month);
                        var day   = parseInt(book.GoodreadsResponse.book.publication_day);
                        pub_date.val( month + '/' + day + '/' + year );

                        pub_fmt.val(
                            book.GoodreadsResponse.book.format
                        );

                        pub_edt.val(
                            book.GoodreadsResponse.book.edition_information
                        );

                        p_count.val(
                            book.GoodreadsResponse.book.num_pages
                        );

                      s_number.val(
                        book.GoodreadsResponse.book.series_works.series_work.user_position
                      );

                        gr_rvw.val(
                            book.GoodreadsResponse.book.average_rating
                        );

                        gr_id.val(
                            book.GoodreadsResponse.book.id
                        );

                        isbn13.val(
                            book.GoodreadsResponse.book.isbn13
                        );

                        asin.val(
                            book.GoodreadsResponse.book.asin
                        );

                        gr_url.val(
                            book.GoodreadsResponse.book.url
                        );

                    }

                }
            });

        });

    });

})(jQuery);
