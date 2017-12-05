<?php {

	$progress         = get_option( $this->widget_id, array() );
	$most_recent      = end( $progress );
	$book_cover       = isset( $most_recent[ 'book_cover' ] ) ? $most_recent[ 'book_cover' ] : '';
	$book_title       = isset( $most_recent[ 'book_title' ] ) ? $most_recent[ 'book_title' ] : '';
	$book_author      = isset( $most_recent[ 'book_author' ] ) ? $most_recent[ 'book_author' ] : '';
	$current_page     = isset( $most_recent[ 'current_page' ] ) ? $most_recent[ 'current_page' ] : 0;
	$num_of_pages     = isset( $most_recent[ 'num_of_pages' ] ) ? $most_recent[ 'num_of_pages' ] : 1;
	$progress_comment = isset( $most_recent[ 'progress_comment' ] ) ? $most_recent[ 'progress_comment' ] : '';
	$finished_book    = isset( $most_recent[ 'finished_book' ] ) ? $most_recent[ 'finished_book' ] : 0;
	$last_updated     = isset( $most_recent[ 'last_updated' ] ) ? $most_recent[ 'last_updated' ] : '';
	$percentage       = ! empty( $num_of_pages ) ? round( ( $current_page / $num_of_pages ) * 100 ) : 0;

?>
    <form id="<?php echo $this->widget_id; ?>">
        <div id="feedback"></div>
        <div class="currently-reading-wrapper">
            <div class="book">
                <?php if( '' !== $book_cover ) : ?>
                    <img src="<?php echo $book_cover; ?>" alt="currently-reading-book-cover" style="width: 100px"
                         id="rcno_currently_reading_cover">
                    <div class="progress-bar">
                         <span class="percentage-value"><?php echo $percentage . '%'?></span>
                        <div class="percentage" style="width: <?php echo $percentage . '%'?>"></div>
                    </div>
                <?php else : ?>
                    <input type="text" id="rcno_currently_reading_upload_field"> <span><input type="button"
                                                                                              class="rcno_currently_upload_button
                button-secondary" value="<?php _e( 'Book cover', 'rcno-reviews' ); ?>"></span>
                <?php endif; ?>
            </div>
            <div class="info">
                <div class="form-field input-text-wrap">
                    <input type="text" id="rcno_currently_reading_book_title" value="<?php echo $book_title; ?>" placeholder="<?php _e( 'Book Title', 'rcno-reviews' ) ?>" <?php if ( '' !== $book_title ) { echo 'disabled'; }; ?> />
                    <input type="text" id="rcno_currently_reading_book_author" value="<?php echo $book_author; ?>" placeholder="<?php _e( 'Book Author', 'rcno-reviews' ) ?>" <?php if ( '' !== $book_author ) { echo 'disabled'; }; ?> >
                    <p class="rcno_current_page"><strong><?php _e( 'Currently on page ', 'rcno-reviews' ) ?> <input
                                    type="number" value="<?php echo $current_page; ?>" id="rcno_current_page_number"
                                    min="1" > / <input type="number" value="<?php
                            echo
                            $num_of_pages; ?>"
                                                       id="rcno_current_num_pages" <?php if ( $num_of_pages > 1 ) {
                                                           echo 'disabled="disabled"'; }; ?> min="1" /></strong></p>
                    <textarea name="" id="rcno_currently_reading_book_comment" cols="30" rows="5" placeholder="<?php _e( 'Comment on progress', 'rcno-reviews' ) ?>..." style="width: 100%" ><?php echo $progress_comment; ?></textarea>
                </div>
            </div>
        </div>
        <div class="finished">
            <button class="button button-primary" id="rcno_currently_reading_update" style="margin-right: 10px;" ><?php _e( 'Update Progress', 'rcno-reviews' ); ?></button>
            <label for="rcno_currently_reading_finished"><input name="rcno_currently_reading_finished" id="rcno_currently_reading_finished" type="checkbox" <?php checked( $finished_book, true, true ); ?> /> <?php _e( 'Finished', 'rcno-reviews' ) ?></label>
	        <?php if ( '' !== $last_updated ) : ?>
                <span class="last-updated">
                    <?php printf( __( '%s ago', 'rcno-reviews' ), human_time_diff( strtotime( $last_updated ),
                        current_time( 'timestamp' ) ) ); ?>
                </span>
	        <?php endif; ?>
        </div>
    </form>

    <style>
        .currently-reading-wrapper input[type=text]:first-child {
            margin: 0 0 10px 0;
            width: 100%;
        }
        .currently-reading-wrapper:after {
            content: "";
            display: block;
            clear: both;
        }
        .currently-reading-wrapper {
            position: relative;
        }
        .currently-reading-wrapper .book {
            width: 100px;
            display: inline-block;
            margin: 0 10px 0 0;
        }
        .currently-reading-wrapper .info {
            display: inline-block;
            float: right;
            width: calc(100% - 110px);
        }
        input#rcno_current_page_number,
        input#rcno_current_num_pages {
            width: 60px !important;
        }
        #rcno_currently_reading .finished {
            position: relative;
            margin: 10px 0 0 0;
        }
        span.last-updated {
            float: right;
            font-style: italic;
            font-size: 12px;
            color: #969696;
        }
        .currently-reading-wrapper .book .progress-bar {
            width: 100%;
            height: 20px;
            background: #ccc;
        }
        .currently-reading-wrapper .book .percentage {
            height: 20px;
            background: red;
        }
        .percentage-value {
            float: right;
            color: #fff;
        }
        div#feedback p {
            background: red;
            color: #fff;
            padding: 4px;
            border-radius: 2px;
            box-shadow: 1px 1px 2px #b2b2b2;
        }
    </style>
<?php } ?>
