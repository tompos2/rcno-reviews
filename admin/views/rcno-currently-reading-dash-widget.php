<?php {

	$progress         = get_option( $this->widget_id, array() );
	$most_recent      = end( $progress );
	$book_title       = isset( $most_recent[ 'book_title' ] ) ? $most_recent[ 'book_title' ] : '';
	$book_author      = isset( $most_recent[ 'book_author' ] ) ? $most_recent[ 'book_author' ] : '';
	$current_page     = isset( $most_recent[ 'current_page' ] ) ? $most_recent[ 'current_page' ] : 1;
	$num_of_pages     = isset( $most_recent[ 'num_of_pages' ] ) ? $most_recent[ 'num_of_pages' ] : 1;
	$progress_comment = isset( $most_recent[ 'progress_comment' ] ) ? $most_recent[ 'progress_comment' ] : '';
	$finished_book    = isset( $most_recent[ 'finished_book' ] ) ? $most_recent[ 'finished_book' ] : 0;
	$last_updated     = isset( $most_recent[ 'last_updated' ] ) ? $most_recent[ 'last_updated' ] : '';

?>
    <form id="<?php echo $this->widget_id; ?>">
        <div id="feedback"></div>
        <div class="book">

        </div>
        <div class="info">
            <div class="form-field input-text-wrap">
                <input type="text" id="rcno_currently_reading_book_title" value="<?php echo $book_title; ?>" placeholder="<?php _e( 'Book Title', 'rcno-reviews' ) ?>" <?php if ( '' !== $book_title ) { echo 'disabled'; }; ?> />
                <input type="text" id="rcno_currently_reading_book_author" value="<?php echo $book_author; ?>" placeholder="<?php _e( 'Book Author', 'rcno-reviews' ) ?>" <?php if ( '' !== $book_author ) { echo 'disabled'; }; ?> >
                <p class="rcno_current_page"><strong><?php _e( 'Currently on page ', 'rcno-reviews' ) ?> <input
                                type="number" value="<?php echo $current_page; ?>" id="rcno_current_page_number"
                                min="1" > / <input type="number" value="<?php echo $num_of_pages; ?>"
                                                   id="rcno_current_num_pages" <?php if ( $num_of_pages > 1 ) {
                                                       echo 'disabled="disabled"'; }; ?> min="1" /></strong></p>
                <textarea name="" id="rcno_currently_reading_book_comment" cols="30" rows="5" placeholder="<?php _e( 'Comment on progress', 'rcno-reviews' ) ?>..." style="width: 100%" ><?php echo $progress_comment; ?></textarea>
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
<?php } ?>