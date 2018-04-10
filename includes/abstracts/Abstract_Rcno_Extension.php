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
	 * Load method used to create hooks to extend or apply new features
	 * This method will be called only on active extensions
	 */
	public function load() {
	}

	/**
	 * Buttons to be shown on the Extensions screen
	 *
	 * @param  array $integrations Array of active integrations.
	 *
	 * @return void
	 */
	public function buttons( $integrations ) {
		if ( ! isset( $integrations[ $this->id ] ) ) { ?>
            <button type="button" data-integration="<?php echo $this->id; ?>"
                    class="button button-primary button-extension-activate"><?php _e( 'Activate', 'rcno-reviews' ); ?></button>
		<?php } else { ?>
            <button type="button" data-integration="<?php echo $this->id; ?>"
                    class="button button-default button-extension-deactivate"><?php _e( 'Deactivate', 'rcno-reviews' ); ?></button>
		<?php }	?>

		<?php
	}
}