<?php

$review_score_criteria = get_post_meta( $review->ID, 'rcno_review_score_criteria', true );
$review_score_type = get_post_meta( $review->ID, 'rcno_review_score_type', true );
$review_score_position = get_post_meta( $review->ID, 'rcno_review_score_position', true );

$review_score_enable = get_post_meta( $review->ID, 'rcno_review_score_enable', true );

wp_nonce_field( 'rcno_repeatable_meta_box_nonce', 'rcno_repeatable_meta_box_nonce' );
?>

<script type="text/javascript">
    jQuery(document).ready(function( $ ){
        $( '#add-row' ).on('click', function() {
            var row = $( '.empty-row.screen-reader-text' ).clone(true);
            row.removeClass( 'empty-row screen-reader-text' );
            row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
            return false;
        });

        $( '.remove-row' ).on('click', function() {
            $(this).parents('tr').remove();
            return false;
        });
    });
</script>

<div class="review-score-metabox-container">

    <div class="review-score-options" style="width:25%; display:inline-block">
        <div class="review-score-rating-enable">
            <label for="rcno_review_score_enable">Enable Rating Box</label>
            <input type="hidden" id="rcno_review_score_enable" name="rcno_review_score_enable" value="0"> <!-- The need for this is weird -->
            <input type="checkbox" id="rcno_review_score_enable" name="rcno_review_score_enable" value="1" <?php checked( $review_score_enable, '1', true ); ?>>
        </div>

        <div class="review-score-rating-type">
            <label for="rcno_review_score_type">Rating Type</label>
            <select id="rcno_review_score_type" name="rcno_review_score_type">
                <option value="number" <?php if ($review_score_type === 'number') echo 'selected="selected"'; ?>>Number</option>
                <option value="letter" <?php if ($review_score_type === 'letter') echo 'selected="selected"'; ?>>Letter Grade</option>
                <option value="stars" <?php if ($review_score_type === 'stars') echo 'selected="selected"'; ?>>Stars</option>
            </select>
        </div>

        <div class="review-score-rating-position">
            <label for="rcno_review_score_position">Rating Box Position</label>
            <select id="rcno_review_score_position" name="rcno_review_score_position">
                <option value="top" <?php if ($review_score_position === 'top') echo 'selected="selected"'; ?>>Top</option>
                <option value="bottom" <?php if ($review_score_position === 'bottom') echo 'selected="selected"'; ?>>Bottom</option>
            </select>
        </div>
    </div>

    <div class="review-score-table" style="width:74%; display:inline-block">

    <table id="repeatable-fieldset-one" width="100%">

        <thead>
        <tr>
            <th width="40%">Label</th>
            <th width="40%">Score</th>
            <th width="8%"></th>
        </tr>
        </thead>
        <tbody>
        <?php

        if ( $review_score_criteria ) :

            foreach ( $review_score_criteria as $field ) {
                ?>
                <tr>
                    <td><input type="text" class="widefat" name="label[]" value="<?php if( $field['label'] !== '' ) echo esc_attr( $field['label'] ); ?>" /></td>

                    <td><input type="number" min="0" max="5" step="0.1" class="widefat" name="score[]" value="<?php if ( $field['score'] !== '' ) echo esc_attr( $field['score'] ); ?>" /></td>

                    <td><a class="button remove-row" href="#">Remove</a></td>
                </tr>
                <?php
            }
        else :
            // show a blank one
            ?>
            <tr>
                <td><input type="text" class="widefat" name="label[]" /></td>

                <td><input type="number" min="0" max="5" step="0.1" class="widefat" name="score[]" /></td>

                <td><a class="button remove-row" href="#">Remove</a></td>
            </tr>
        <?php endif; ?>

        <!-- empty hidden one for jQuery -->
        <tr class="empty-row screen-reader-text">
            <td><input type="text" class="widefat" name="label[]" /></td>

            <td><input type="number" min="0" max="5" step="0.1" class="widefat" name="score[]" /></td>

            <td><a class="button remove-row" href="#">Remove</a></td>
        </tr>
        </tbody>

    </table>
        <p style="margin:0;font-size:10px;text-align:right;">Label: The name of criteria - Score: A number value between 0 - 5, in increments of 0.1</p>
        <p><a id="add-row" class="button button-primary" href="#">Add Criteria</a></p>

    </div>

</div>
