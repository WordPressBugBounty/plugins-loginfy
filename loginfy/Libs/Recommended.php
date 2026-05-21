<?php
namespace Loginfy\Libs;

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Recommended' ) ) {

	/**
	 * Recommended Class
	 *
	 * Adds a submenu that links to the WordPress plugin install screen
	 * filtered by author.
	 *
	 * Jewel Theme <support@jeweltheme.com>
	 */
	class Recommended {

		public $parent_slug;
		public $menu_title;
		public $author;
		public $menu_order;

		/**
		 * Constructor.
		 *
		 * @param string  $parent_slug Parent menu slug to attach submenu under.
		 * @param string  $author      WordPress.org plugin author slug to filter by.
		 * @param string  $menu_title  Submenu label.
		 * @param integer $menu_order  Submenu order priority.
		 */
		public function __construct( $parent_slug = '', $author = 'pixarlabs', $menu_title = '', $menu_order = 99 ) {
			$this->parent_slug = $parent_slug;
			$this->author      = $author;
			$this->menu_title  = $menu_title ? $menu_title : __( 'Recommended', 'loginfy' );
			$this->menu_order  = $menu_order;

			add_action( 'admin_menu', array( $this, 'admin_menu' ), $this->menu_order );
		}

		/**
		 * Build the plugin-install URL filtered by author.
		 */
		public function get_recommended_url() {
			return admin_url( 'plugin-install.php?tab=search&type=author&s=' . rawurlencode( $this->author ) );
		}

		/**
		 * Register submenu pointing directly to plugin-install screen.
		 */
		public function admin_menu() {
			if ( empty( $this->parent_slug ) ) {
				return;
			}

			add_submenu_page(
				$this->parent_slug,
				$this->menu_title,
				$this->menu_title,
				'install_plugins',
				$this->get_recommended_url()
			);
		}
	}
}
