<?php
/**
 * Abstract Extension Class from which all others should be done
 */
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

abstract class Abstract_Rcno_Extension {

	/**
	 * Extension ID
	 *
	 * @var string
	 */
	public $id = '';

	/**
	 * Active Indicator
	 *
	 * @var boolean
	 */
	public $active = false;
	/**
	 * Integration Image
	 *
	 * @var string
	 */
	public $image = '';
	/**
	 * Integration Title
	 *
	 * @var string
	 */
	public $title = '';
	/**
	 * Integration Description
	 *
	 * @var string
	 */
	public $desc = '';

	/**
	 * Integration Settings page
	 *
	 * @var bool
	 */
	public $settings = false;

	/**
	 * Integration Settings page action
	 *
	 * @var string
	 */
	public $action = '';

	/**
	 * Load method used to create hooks to extend or apply new features
	 * This method will be called only on active extensions
	 */
	public function load() {
	}

	public function settings_page() {
		$url = add_query_arg( array(
			'action'    => $this->action,
			'TB_iframe' => 'true',
			'width'     => '600',
			'height'    => '400'
		), admin_url( 'admin.php' ) );

		return $url;
	}

	/**
	 * Buttons to be shown on the Extensions screen
	 *
	 * @param  array $extension Array of active extension.
	 *
	 * @return void
	 */
	public function buttons( $extension ) {
		if ( ! isset( $extension[ $this->id ] ) ) { ?>
            <button type="button" data-extension="<?php echo $this->id; ?>"
                    class="button button-primary button-extension-activate"><?php _e( 'Activate', 'rcno-reviews' ); ?></button>
		<?php } else { ?>
            <button type="button" data-extension="<?php echo $this->id; ?>"
                    class="button button-default button-extension-deactivate"><?php _e( 'Deactivate', 'rcno-reviews' ); ?></button>
            <?php if ( $this->settings ) { ?>
                <a href="<?php echo $this->settings_page() ?>" class="button button-primary thickbox"><?php _e( 'Settings', 'rcno-reviews' ); ?></a>
            <?php } ?>
		<?php }	?>

		<?php
	}
}