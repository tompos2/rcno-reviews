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
    function renderMediaUploader() {

        var file_frame, image_data;

        /**
         * If an instance of file_frame already exists, then we can open it
         * rather than creating a new instance.
         */
        if ( undefined !== file_frame ) {

            file_frame.open();
            return;

        }

        /**
         * If we're this far, then an instance does not exist, so we need to
         * create our own.
         *
         * Here, use the wp.media library to define the settings of the Media
         * Uploader. We're opting to use the 'post' frame which is a template
         * defined in WordPress core and are initializing the file frame
         * with the 'insert' state.
         *
         * We're also not allowing the user to select more than one image.
         */
        file_frame = wp.media.frames.file_frame = wp.media({
            frame:    'post',
            state:    'insert',
            multiple: false
        });

        /**
         * Setup an event handler for what to do when an image has been
         * selected.
         *
         * Since we're using the 'view' state when initializing
         * the file_frame, we need to make sure that the handler is attached
         * to the insert event.
         */
        file_frame.on( 'insert', function() {

            // Read the JSON data returned from the Media Uploader.
            var json = file_frame.state().get('selection').first().toJSON();

            // First, make sure that we have the URL of an image to display.
            if ( 0 > $.trim( json.url.length ) ) {
                return;
            }

            // After that, set the properties of the image and display it.
            $( '#rcno-reviews-book-cover-container' )
                .children( 'img' )
                .attr( 'src', json.url )
                .attr( 'alt', json.caption )
                .attr( 'title', json.title )
                .show()
                .parent()
                .removeClass( 'hidden' );

            // Next, hide the anchor responsible for allowing the user to select an image
            $( '#rcno-add-book-cover' ).hide();
            $( '#rcno-remove-book-cover' ).parent().removeClass( 'hidden' );


            $( '#rcno-reviews-book-cover-src' ).val( json.url );
            $( '#rcno-reviews-book-cover-title' ).val( json.title );
            $( '#rcno-reviews-book-cover-alt' ).val( json.alt );

        });

        // Now display the actual file_frame
        file_frame.open();

    }

    function resetUploadForm( $ ) {

        // First, we'll hide the image
        $( '#rcno-reviews-book-cover-container' )
            .children( 'img' )
            .hide();

        $('#rcno-add-book-cover')
            .parent()
            .show();

        // Finally, we add the 'hidden' class back to this anchor's parent
        $( '#rcno-remove-book-cover' )
            .addClass( 'hidden' );

    }

    function renderFeaturedImage( $ ) {

        /* If a thumbnail URL has been associated with this image
         * Then we need to display the image and the reset link.
         */
        if ( '' !== $.trim ( $( '#rcno-reviews-book-cover-src' ).val() ) ) {

            $( '#rcno-reviews-book-cover-container' ).removeClass( 'hidden' );

            $( '#rcno-add-book-cover' )
                .parent()
                .hide();

            $( '#rcno-remove-book-cover' )
                .parent()
                .removeClass( 'hidden' );

        }

    }

    $(function() {

        renderFeaturedImage( $ );

        $( '#rcno-add-book-cover' ).on( 'click', function( e ) {
          e.preventDefault();

            // Display the media uploader.
            renderMediaUploader();
        });

        $( '#rcno-remove-book-cover' ).on( 'click', function( e ) {
            e.preventDefault();

            // Remove the image, toggle the anchors.
            resetUploadForm( $ );

        });
    });

    $(function() {
        $('rcno-isbn-fetch').on('click', function(e) {
            e.preventDefault();

            var book_isbn = $('#rcno_book_isbn').val();
            var google_url = 'https://www.googleapis.com/books/v1/volumes?q=isbn:';
            var api_url = google_url + book_isbn;

            $.ajax({
                url: api_url,
                type: 'GET',
                success: function (book) {
                    console.log(book);
                },
                error: function () {

                }
            });

        });


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

                    if (book['error']) {

                        $('.book-isbn-metabox-error').show();

                    } else {

                        $('#title').val(
                            book['GoodreadsResponse']['book']['title']
                        );

                        $('#rcno_book_title').val(
                            book['GoodreadsResponse']['book']['work']['original_title']
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

                        $('#rcno_book_pub_edition').val(
                            book['GoodreadsResponse']['book']['edition_information']
                        );

                        $('#rcno_book_page_count').val(
                            book['GoodreadsResponse']['book']['num_pages']
                        );

                        $('#rcno_book_gr_review').val(
                            book['GoodreadsResponse']['book']['average_rating']
                        );

                        $('#rcno_book_gr_id').val(
                            book['GoodreadsResponse']['book']['id']
                        );

                        $('#rcno_book_isbn13').val(
                            book['GoodreadsResponse']['book']['isbn13']
                        );

                        $('#rcno_book_asin').val(
                            book['GoodreadsResponse']['book']['asin']
                        );

                        $('#rcno_book_gr_url').val(
                            book['GoodreadsResponse']['book']['url']
                        );

                    }

                }
            });

        })

    });

})(jQuery);