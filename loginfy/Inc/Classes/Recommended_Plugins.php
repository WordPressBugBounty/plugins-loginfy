<?php
namespace Loginfy\Inc\Classes;

use Loginfy\Libs\Recommended;

if ( ! class_exists( 'Recommended_Plugins' ) ) {
	/**
	 * Recommended Plugins class
	 *
	 * Jewel Theme <support@jeweltheme.com>
	 */
	class Recommended_Plugins extends Recommended {

		/**
		 * Constructor.
		 */
		public function __construct() {
			parent::__construct(
				'loginfy-customizer',
				'pixarlabs',
				__( 'Recommended', 'loginfy' ),
				51
			);
		}
	}
}
