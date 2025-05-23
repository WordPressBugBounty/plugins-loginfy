<?php
namespace Loginfy\Inc\Classes\Notifications\Model;

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract class for Popup
 *
 * Jewel Theme <support@jeweltheme.com>
 */
abstract class Popup extends Notification {

	public $type = 'popup';

	/**
	 * Get Key
	 *
	 * @author Jewel Theme <support@jeweltheme.com>
	 */
	final public function get_key() {
		return 'jlt_loginfy_popup_' . $this->get_id();
	}
}