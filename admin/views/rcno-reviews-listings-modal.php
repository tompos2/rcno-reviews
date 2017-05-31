<?php
/**
 * The shortcode overlay view (aka the dialog itself) to insert listings shortcodes.
 *
 * @since      1.0.0
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin
 * @author     wzyMedia <wzy@outlook.com>
 */
?>
<div id="rcno-modal-backdrop-scl" style="display: none"></div>

<div id="rcno-modal-wrap-scl" class="wp-core-ui search-panel-visible" style="display: none">
	<form id="rcno-modal-form-scl" tabindex="-1">
		<?php wp_nonce_field( 'rcno-ajax-nonce', 'rcno_ajax_nonce', false ); ?>
		<div id="rcno-modal-title-scl">
			<?php _e( 'Insert book review listing', 'rcno-reviews' ) ?>
			<button type="button" id="rcno-modal-close-scl"><span class="screen-reader-text"><?php _e( 'Close' ); ?></span></button>
		</div>
		<div id="rcno-modal-panel-scl">
			<ul id="rcno-modal-scl-mode">
				<li>
					<input type="radio" selected="selected" value="rcno-tax-list" id="rcno-modal-scl-mode-tax" name="rcno-modal-scl-mode" />
					<label for="rcno-modal-scl-mode-tax"><b><?php _e('Embed taxonomy index', 'rcno-reviews'); ?></b></label>
					<div id="rcno-taxonomy-panel">
						<label><span><?php _e('Taxonomy', 'rcno-reviews' ); ?></span></label>
						<select id="review-taxonomy">
							<?php if( is_array(  Rcno_Reviews_Option::get_option( 'rcno_taxonomy_selection' ) ) ) {
								foreach( Rcno_Reviews_Option::get_option( 'rcno_taxonomy_selection' ) as $key => $val ){ ?>
										<option value="<?php echo 'rcno_' . $key ?>" ><?php echo $val ?></option>
								<?php }
							} ?>
						</select>
					</div>
				</li>
				<li>
					<input type="radio" value="rcno-review-index" name="rcno-modal-scl-mode" id="rcno-modal-scl-mode-ind"/>
					<label for="rcno-modal-scl-mode-ind"><b><?php _e('Embed book review index', 'rcno-reviews'); ?></b></label>
				</li>
			</ul>
		</div>
		<div class="submitbox">
			<div id="rcno-modal-cancel-scl">
				<a class="submitdelete deletion" href="#"><?php _e( 'Cancel' ); ?></a>
			</div>
			<div id="rcno-modal-update-scl">
				<input type="submit" value="<?php esc_attr_e( 'Include Shortcut', 'rcno-reviews' ); ?>" class="button button-primary" id="rcno-modal-submit-scl" name="rcno-modal-submit-scl">
			</div>
		</div>
	</form>
</div>