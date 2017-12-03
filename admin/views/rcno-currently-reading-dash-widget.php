<?php {

	$progress = get_option( $this->widget_id, array() );
	$most_recent = end($progress);

?>

	<form id="<?php echo $this->widget_id; ?>" >
		<div id="feedback"></div>
		<div>
			<label for="industry">
				<?php esc_html_e( 'Industry', 'text-domain' ); ?>
			</label>
			<input id="industry" type="text" />
		</div>
		<div>
			<label for="amount">
				<?php esc_html_e( 'Amount', 'text-domain' ); ?>
			</label>
			<input id="amount" type="number" min="0" max="100" />
		</div>
		<?php submit_button( __( 'Update Progress', 'rcno-reviews' ) ); ?>
	</form>

    <?php var_dump($most_recent, $progress); ?>

<?php } ?>