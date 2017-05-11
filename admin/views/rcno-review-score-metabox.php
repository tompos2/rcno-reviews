<?php

$repeatable_fields = get_post_meta( $review->ID, 'repeatable_fields', true );
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

	if ( $repeatable_fields ) :

		foreach ( $repeatable_fields as $field ) {
			?>
            <tr>
                <td><input type="text" class="widefat" name="label[]" value="<?php if( $field['label'] !== '' ) echo esc_attr( $field['label'] ); ?>" /></td>

                <td><input type="number" min="0" max="10" step="0.1" class="widefat" name="score[]" value="<?php if ( $field['score'] !== '' ) echo esc_attr( $field['score'] ); ?>" /></td>

                <td><a class="button remove-row" href="#">Remove</a></td>
            </tr>
			<?php
		}
	else :
		// show a blank one
		?>
        <tr>
            <td><input type="text" class="widefat" name="label[]" /></td>

            <td><input type="number" min="0" max="10" step="0.1" class="widefat" name="score[]" /></td>

            <td><a class="button remove-row" href="#">Remove</a></td>
        </tr>
	<?php endif; ?>

    <!-- empty hidden one for jQuery -->
    <tr class="empty-row screen-reader-text">
        <td><input type="text" class="widefat" name="label[]" /></td>

        <td><input type="number" min="0" max="10" step="0.1" class="widefat" name="score[]" /></td>

        <td><a class="button remove-row" href="#">Remove</a></td>
    </tr>
    </tbody>
</table>

<p><a id="add-row" class="button button-primary" href="#">Add Criteria</a></p>
