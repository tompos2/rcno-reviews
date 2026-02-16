<?php

$term = get_the_term_list( $post->ID, $box['args']['taxonomy'] );

if ( false === $term || is_wp_error( $term ) ) {
	$term = '';
}

?>

<div class="taxonomy-metabox">
    <input type="text" name="<?php echo esc_attr( $box['args']['taxonomy'] . '_taxonomy_metabox' ); ?>"
           id="<?php echo esc_attr( $box['args']['taxonomy'] . '_taxonomy_metabox' ); ?>"
           value="<?php echo esc_attr( $term ); ?>"/>
	<?php wp_nonce_field( $box['args']['taxonomy'] . '_metabox', $box['args']['taxonomy'] . '_nonce' ); ?>
</div>
