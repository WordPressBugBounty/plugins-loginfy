<?php

namespace Loginfy\Inc\Core\Inc;

use Loginfy\Inc\Core\LoginCustomizer;
use Loginfy\Inc\Core\Inc\Settings\Templates;
use Loginfy\Inc\Core\Inc\Settings\Logo_Section;
use Loginfy\Inc\Core\Inc\Settings\Layout_Section;
use Loginfy\Inc\Core\Inc\Settings\Form_Section;
use Loginfy\Inc\Core\Inc\Settings\Background_Section;
use Loginfy\Inc\Core\Inc\Settings\Login_Form_Fields;
use Loginfy\Inc\Core\Inc\Settings\Button_Section;
use Loginfy\Inc\Core\Inc\Settings\Others_Section;
use Loginfy\Inc\Core\Inc\Settings\Google_Fonts;
use Loginfy\Inc\Core\Inc\Settings\Error_Messages;
use Loginfy\Inc\Core\Inc\Settings\Custom_CSS_JS;
use Loginfy\Inc\Core\Inc\Settings\Credits_Section;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'Customize_Settings' ) ) {

	class Customize_Settings extends Customize_Model {

		public $defaults = [];
		public $options;

		public function __construct() {
			// this should be first so the default values get stored
			$this->login_customizer_options();
			$this->options = (array) get_option( $this->prefix );

			// $options = $this->validation_options( $options );
			parent::__construct( $this->options );
		}


		protected function get_defaults() {
			return $this->defaults;
		}

		public function login_customizer_options() {
			if ( ! class_exists( 'LOGINFY' ) ) {
				return;
			}

			// Create customize options
			\LOGINFY::createCustomizeOptions(
				$this->prefix,
				[
					'database'        => 'option',
					'transport'       => 'postMessage',
					'capability'      => 'manage_options',
					'save_defaults'   => true,
					'enqueue_webfont' => true,
					'async_webfont'   => false,
					'output_css'      => true,
				]
			);

			$this->defaults = array_merge( $this->defaults, ( new Templates() )->get_defaults() );
			$this->defaults = array_merge( $this->defaults, ( new Logo_Section() )->get_defaults() );
			$this->defaults = array_merge( $this->defaults, ( new Background_Section() )->get_defaults() );
			$this->defaults = array_merge( $this->defaults, ( new Layout_Section() )->get_defaults() );
			$this->defaults = array_merge( $this->defaults, ( new Form_Section() )->get_defaults() );
			$this->defaults = array_merge( $this->defaults, ( new Login_Form_Fields() )->get_defaults() );
			$this->defaults = array_merge( $this->defaults, ( new Button_Section() )->get_defaults() );
			$this->defaults = array_merge( $this->defaults, ( new Others_Section() )->get_defaults() );
			$this->defaults = array_merge( $this->defaults, ( new Google_Fonts() )->get_defaults() );
			$this->defaults = array_merge( $this->defaults, ( new Error_Messages() )->get_defaults() );
			$this->defaults = array_merge( $this->defaults, ( new Credits_Section() )->get_defaults() );
			$this->defaults = array_merge( $this->defaults, ( new Custom_CSS_JS() )->get_defaults() );
		}
	}
}
