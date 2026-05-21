<?php

namespace Loginfy\Inc\Core\Inc\Settings;

use Loginfy\Libs\Helper;
use Loginfy\Inc\Core\Inc\Customize_Model;
if ( !defined( 'ABSPATH' ) ) {
    die;
}
// Cannot access directly.
class Form_Section extends Customize_Model {
    public function __construct() {
        $this->form_customizer();
    }

    public function get_defaults() {
        return [
            'login_form_bg_type'       => 'color',
            'login_form_bg_color'      => [
                'background-color'      => '',
                'background-position'   => 'center center',
                'background-repeat'     => 'repeat-x',
                'background-attachment' => 'fixed',
                'background-size'       => 'cover',
            ],
            'login_form_bg_gradient'   => [
                'background-color'              => '#009e44',
                'background-gradient-color'     => '#81d742',
                'background-gradient-direction' => '135deg',
            ],
            'login_form_height_width'  => [
                'width'  => '',
                'height' => '',
                'unit'   => 'px',
            ],
            'login_form_margin'        => '',
            'login_form_padding'       => '',
            'login_form_border'        => '',
            'login_form_border_radius' => '',
            'login_form_box_shadow'    => [
                'bs_color'      => 'transparent',
                'bs_hz'         => '',
                'bs_ver'        => '',
                'bs_blur'       => '',
                'bs_spread'     => '',
                'bs_spread_pos' => '',
            ],
        ];
    }

    /**
     * Login Form Box Shadow
     *
     * @param [type] $login_form_box_shadow
     *
     * @return void
     */
    public function login_form_box_shadow_settings( &$login_form_box_shadow ) {
        $login_form_box_shadow[] = [
            'type'    => 'notice',
            'title'   => 'Color',
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro(),
        ];
        $login_form_box_shadow[] = [
            'type'    => 'notice',
            'title'   => 'Horizontal',
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro(),
        ];
        $login_form_box_shadow[] = [
            'type'    => 'notice',
            'title'   => 'Vertical',
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro(),
        ];
        $login_form_box_shadow[] = [
            'type'    => 'notice',
            'title'   => 'Blur',
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro(),
        ];
        $login_form_box_shadow[] = [
            'type'    => 'notice',
            'title'   => 'Spread',
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro(),
        ];
        $login_form_box_shadow[] = [
            'type'    => 'notice',
            'title'   => 'Position',
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro(),
        ];
    }

    /**
     * Login Form Settings
     */
    public function login_form_settings( &$login_form_settings ) {
        $login_form_box_shadow = [];
        $this->login_form_box_shadow_settings( $login_form_box_shadow );
        $login_form_settings[] = [
            'type'    => 'subheading',
            'content' => 'Background',
        ];
        $login_form_settings[] = [
            'id'      => 'login_form_bg_type',
            'type'    => 'button_set',
            'options' => [
                'color'    => 'Color & Image',
                'gradient' => 'Gradient',
            ],
            'default' => $this->get_default_field( 'login_form_bg_type' ),
        ];
        $login_form_settings[] = [
            'id'         => 'login_form_bg_color',
            'type'       => 'background',
            'title'      => 'Background',
            'default'    => $this->get_default_field( 'login_form_bg_color' ),
            'dependency' => [
                'login_form_bg_type',
                '==',
                'color',
                'all'
            ],
        ];
        $login_form_settings[] = [
            'type'       => 'notice',
            'title'      => 'Background Option with Gradient Color',
            'style'      => 'warning',
            'content'    => Helper::loginfy_upgrade_pro(),
            'dependency' => [
                'login_form_bg_type',
                '==',
                'gradient',
                'all'
            ],
        ];
        $login_form_settings[] = [
            'type'    => 'subheading',
            'content' => 'Form Width & Height',
        ];
        $login_form_settings[] = [
            'id'      => 'login_form_height_width',
            'type'    => 'dimensions',
            'title'   => 'Width & Height',
            'default' => $this->get_default_field( 'login_form_height_width' ),
        ];
        $login_form_settings[] = [
            'type'    => 'notice',
            'title'   => 'Margin',
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro(),
        ];
        $login_form_settings[] = [
            'type'    => 'notice',
            'title'   => 'Padding',
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro(),
        ];
        $login_form_settings[] = [
            'type'  => 'subheading',
            'title' => 'Border',
        ];
        $login_form_settings[] = [
            'id'      => 'login_form_border',
            'type'    => 'border',
            'default' => $this->get_default_field( 'login_form_border' ),
            'title'   => 'Border',
        ];
        $login_form_settings[] = [
            'type'    => 'notice',
            'title'   => 'Border Radius',
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro(),
        ];
        $login_form_settings[] = [
            'type'    => 'subheading',
            'content' => 'Box Shadow',
        ];
        $login_form_settings[] = [
            'id'     => 'login_form_box_shadow',
            'type'   => 'fieldset',
            'fields' => $login_form_box_shadow,
        ];
    }

    public function form_customizer() {
        if ( !class_exists( 'LOGINFY' ) ) {
            return;
        }
        $login_form_settings = [];
        $this->login_form_settings( $login_form_settings );
        /**
         * Section: Form Options
         */
        \LOGINFY::createSection( $this->prefix, [
            'assign' => 'jlt_loginfy_customizer_login_form_section',
            'title'  => 'Login Form',
            'fields' => $login_form_settings,
        ] );
    }

}
