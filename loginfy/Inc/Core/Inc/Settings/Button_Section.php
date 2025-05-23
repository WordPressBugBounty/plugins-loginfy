<?php

namespace Loginfy\Inc\Core\Inc\Settings;

use Loginfy\Libs\Helper;
use Loginfy\Inc\Core\Inc\Customize_Model;
if ( !defined( 'ABSPATH' ) ) {
    die;
}
// Cannot access directly.
class Button_Section extends Customize_Model {
    public function __construct() {
        $this->button_customizer();
    }

    public function get_defaults() {
        return [
            'button_size'                => '',
            'button_font_size'           => '',
            'login_form_button_settings' => [
                'button_bg'                => 'transparent',
                'button_text_color'        => '',
                'button_text_shadow'       => [
                    'ts_color' => 'transparent',
                    'ts_blur'  => '',
                    'ts_hz'    => '',
                    'ts_ver'   => '',
                ],
                'button_bg_hover'          => 'transparent',
                'button_text_hover'        => 'transparent',
                'button_text_shadow_hover' => [
                    'ts_hover'      => 'transparent',
                    'ts_blur_hover' => '',
                    'ts_hz_hover'   => '',
                    'ts_ver_hover'  => '',
                ],
                'button_margin'            => '',
                'button_padding'           => '',
                'button_border'            => '',
                'button_border_radius'     => '',
                'button_box_shadow'        => [
                    'bs_color'      => 'transparent',
                    'bs_hz'         => '',
                    'bs_ver'        => '',
                    'bs_blur'       => '',
                    'bs_spread'     => '',
                    'bs_spread_pos' => '',
                ],
            ],
        ];
    }

    /**
     * Button Fields: Normal
     *
     * @param [type] $button_fields_normal
     *
     * @return void
     */
    public function button_fields_normal_settings( &$button_fields_normal ) {
        $normal_text_shadow = [];
        $this->normal_button_text_shadow( $normal_text_shadow );
        $button_fields_normal[] = [
            'id'      => 'button_bg',
            'type'    => 'color',
            'title'   => __( 'Background', 'loginfy' ),
            'default' => $this->get_default_field( 'login_form_button_settings' )['button_bg'],
            'class'   => 'loginfy-cs',
        ];
        $button_fields_normal[] = [
            'id'      => 'button_text_color',
            'type'    => 'color',
            'title'   => __( 'Text Color', 'loginfy' ),
            'default' => $this->get_default_field( 'login_form_button_settings' )['button_text_color'],
            'class'   => 'loginfy-cs',
        ];
        // array(
        // 'id'      => 'button_border_color',
        // 'type'    => 'border',
        // 'title'   => 'Border'
        // ),
        // array(
        // 'id'    => 'button_border_radius',
        // 'type'  => 'spacing',
        // 'title' => 'Border Radius',
        // ),
        $button_fields_normal[] = [
            'type'    => 'subheading',
            'content' => __( 'Text Shadow', 'loginfy' ),
        ];
        $button_fields_normal[] = [
            'id'     => 'button_text_shadow',
            'type'   => 'fieldset',
            'fields' => $normal_text_shadow,
        ];
        // array(
        // 'type'    => 'subheading',
        // 'content' => 'Box Shadow',
        // ),
        // array(
        // 'id'     => 'button_box_shadow',
        // 'type'   => 'fieldset',
        // 'fields' => array(
        // array(
        // 'id'      => 'bs_color',
        // 'type'    => 'color',
        // 'title'   => 'Color',
        // 'default' => 'transparent',
        // 'class'   => 'loginfy-cs',
        // ),
        // array(
        // 'id'    => 'bs_hz',
        // 'type'  => 'slider',
        // 'title' => 'Horizontal',
        // ),
        // array(
        // 'id'    => 'bs_ver',
        // 'type'  => 'slider',
        // 'title' => 'Vertical',
        // ),
        // array(
        // 'id'    => 'bs_blur',
        // 'type'  => 'slider',
        // 'title' => 'Blur',
        // ),
        // array(
        // 'id'    => 'bs_spread',
        // 'type'  => 'slider',
        // 'title' => 'Spread',
        // ),
        // array(
        // 'id'      => 'bs_spread_pos',
        // 'type'    => 'select',
        // 'title'   => 'Position',
        // 'options' => array(
        // ''        => 'Outline',
        // 'inset'   => 'Inset'
        // ),
        // 'default' => ''
        // ),
        // )
        // )
    }

    /**
     * Button Text Shadow
     */
    public function normal_button_text_shadow( &$normal_text_shadow ) {
        $normal_text_shadow[] = [
            'type'    => 'notice',
            'title'   => __( 'Color', 'loginfy' ),
            'style'   => 'warning',
            'class'   => 'loginfy-cs',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
        $normal_text_shadow[] = [
            'type'    => 'notice',
            'title'   => __( 'Blur', 'loginfy' ),
            'style'   => 'warning',
            'class'   => 'loginfy-cs',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
        $normal_text_shadow[] = [
            'type'    => 'notice',
            'title'   => __( 'Horizontal', 'loginfy' ),
            'style'   => 'warning',
            'class'   => 'loginfy-cs',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
        $normal_text_shadow[] = [
            'type'    => 'notice',
            'title'   => __( 'Vertical', 'loginfy' ),
            'style'   => 'warning',
            'class'   => 'loginfy-cs',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
    }

    /**
     * * Button Fields: Hover
     *
     * @param [type] $button_fields_hover
     *
     * @return void
     */
    public function button_fields_hover_settings( &$button_fields_hover ) {
        $hover_text_shadow = [];
        $this->hover_text_shadow( $hover_text_shadow );
        $button_fields_hover[] = [
            'id'      => 'button_bg_hover',
            'type'    => 'color',
            'title'   => __( 'Background', 'loginfy' ),
            'default' => $this->get_default_field( 'login_form_button_settings' )['button_bg_hover'],
            'class'   => 'loginfy-cs',
        ];
        $button_fields_hover[] = [
            'id'      => 'button_text_hover',
            'type'    => 'color',
            'title'   => __( 'Text Color', 'loginfy' ),
            'default' => $this->get_default_field( 'login_form_button_settings' )['button_text_hover'],
            'class'   => 'loginfy-cs',
        ];
        // array(
        // 'id'      => 'button_border_color_hover',
        // 'type'    => 'border',
        // 'title'   => 'Border'
        // ),
        // array(
        // 'id'    => 'button_border_radius_hover',
        // 'type'  => 'spacing',
        // 'title' => 'Border Radius',
        // ),
        $button_fields_hover[] = [
            'type'    => 'subheading',
            'content' => __( 'Text Shadow', 'loginfy' ),
        ];
        $button_fields_hover[] = [
            'id'     => 'button_text_shadow_hover',
            'type'   => 'fieldset',
            'fields' => $hover_text_shadow,
        ];
        // Turned off for future use
        // array(
        // 'type'    => 'subheading',
        // 'content' => 'Box Shadow',
        // ),
        // array(
        // 'id'     => 'button_box_shadow_hover',
        // 'type'   => 'fieldset',
        // 'fields' => array(
        // array(
        // 'id'      => 'bs_color',
        // 'type'    => 'color',
        // 'title'   => 'Color',
        // 'default' => 'transparent',
        // 'class'   => 'loginfy-cs',
        // ),
        // array(
        // 'id'    => 'bs_hz',
        // 'type'  => 'slider',
        // 'title' => 'Horizontal',
        // ),
        // array(
        // 'id'    => 'bs_ver',
        // 'type'  => 'slider',
        // 'title' => 'Vertical',
        // ),
        // array(
        // 'id'    => 'bs_blur',
        // 'type'  => 'slider',
        // 'title' => 'Blur',
        // ),
        // array(
        // 'id'    => 'bs_spread',
        // 'type'  => 'slider',
        // 'title' => 'Spread',
        // ),
        // array(
        // 'id'      => 'bs_spread_pos',
        // 'type'    => 'select',
        // 'title'   => 'Position',
        // 'options' => array(
        // ''        => 'Outline',
        // 'inset'   => 'Inset'
        // ),
        // 'default' => ''
        // ),
        // )
        // )
    }

    /**
     * Hover Text Shadow
     */
    public function hover_text_shadow( &$hover_text_shadow ) {
        $hover_text_shadow[] = [
            'type'    => 'notice',
            'title'   => __( 'Color', 'loginfy' ),
            'style'   => 'warning',
            'class'   => 'loginfy-cs',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
        $hover_text_shadow[] = [
            'type'    => 'notice',
            'title'   => __( 'Blur', 'loginfy' ),
            'style'   => 'warning',
            'class'   => 'loginfy-cs',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
        $hover_text_shadow[] = [
            'type'    => 'notice',
            'title'   => __( 'Horizontal', 'loginfy' ),
            'style'   => 'warning',
            'class'   => 'loginfy-cs',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
        $hover_text_shadow[] = [
            'type'    => 'notice',
            'title'   => __( 'Vertical', 'loginfy' ),
            'style'   => 'warning',
            'class'   => 'loginfy-cs',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
    }

    /**
     * Button Fields: Advance
     *
     * @param [type] $button_fields_advance
     *
     * @return void
     */
    public function button_fields_advance_settings( &$button_fields_advance ) {
        $advance_box_shadow = [];
        $this->advance_box_shadow( $advance_box_shadow );
        $button_fields_advance[] = [
            'id'      => 'button_margin',
            'type'    => 'spacing',
            'title'   => __( 'Margin', 'loginfy' ),
            'default' => $this->get_default_field( 'login_form_button_settings' )['button_margin'],
        ];
        $button_fields_advance[] = [
            'id'      => 'button_padding',
            'type'    => 'spacing',
            'title'   => __( 'Padding', 'loginfy' ),
            'default' => $this->get_default_field( 'login_form_button_settings' )['button_padding'],
        ];
        $button_fields_advance[] = [
            'type'    => 'subheading',
            'content' => __( 'Border', 'loginfy' ),
        ];
        $button_fields_advance[] = [
            'id'      => 'button_border',
            'type'    => 'border',
            'title'   => __( 'Border', 'loginfy' ),
            'default' => $this->get_default_field( 'login_form_button_settings' )['button_border'],
        ];
        $button_fields_advance[] = [
            'type'    => 'notice',
            'title'   => __( 'Border Radius', 'loginfy' ),
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
        $button_fields_advance[] = [
            'type'    => 'subheading',
            'content' => __( 'Box Shadow', 'loginfy' ),
        ];
        $button_fields_advance[] = [
            'id'     => 'button_box_shadow',
            'type'   => 'fieldset',
            'fields' => $advance_box_shadow,
        ];
    }

    /**
     * Advance Box Shadow
     */
    public function advance_box_shadow( &$advance_box_shadow ) {
        $advance_box_shadow[] = [
            'type'    => 'notice',
            'title'   => __( 'Color', 'loginfy' ),
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
        $advance_box_shadow[] = [
            'type'    => 'notice',
            'title'   => __( 'Horizontal', 'loginfy' ),
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
        $advance_box_shadow[] = [
            'type'    => 'notice',
            'title'   => __( 'Vertical', 'loginfy' ),
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
        $advance_box_shadow[] = [
            'type'    => 'notice',
            'title'   => __( 'Vertical', 'loginfy' ),
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
        $advance_box_shadow[] = [
            'type'    => 'notice',
            'title'   => __( 'Spread', 'loginfy' ),
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
        $advance_box_shadow[] = [
            'type'    => 'notice',
            'title'   => __( 'Position', 'loginfy' ),
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
    }

    public function button_customizer_settings( &$button_customizer_settings ) {
        $button_fields_normal = [];
        $this->button_fields_normal_settings( $button_fields_normal );
        $button_fields_hover = [];
        $this->button_fields_hover_settings( $button_fields_hover );
        $button_fields_advance = [];
        $this->button_fields_advance_settings( $button_fields_advance );
        $button_customizer_settings[] = [
            'type'    => 'subheading',
            'content' => __( 'General Settings', 'loginfy' ),
        ];
        $button_customizer_settings[] = [
            'type'    => 'notice',
            'title'   => __( 'Button Width & Height', 'loginfy' ),
            'style'   => 'warning',
            'content' => Helper::loginfy_upgrade_pro( ' ' ),
        ];
        $button_customizer_settings[] = [
            'id'      => 'button_font_size',
            'type'    => 'slider',
            'unit'    => 'px',
            'title'   => __( 'Font Size', 'loginfy' ),
            'default' => $this->get_default_field( 'button_font_size' ),
        ];
        $button_customizer_settings[] = [
            'id'    => 'login_form_button_settings',
            'type'  => 'tabbed',
            'title' => __( 'Button', 'loginfy' ),
            'tabs'  => [[
                'title'  => __( 'Normal', 'loginfy' ),
                'fields' => $button_fields_normal,
            ], [
                'title'  => __( 'Hover', 'loginfy' ),
                'fields' => $button_fields_hover,
            ], [
                'title'  => __( 'Advanced', 'loginfy' ),
                'fields' => $button_fields_advance,
            ]],
        ];
    }

    public function button_customizer() {
        if ( !class_exists( 'LOGINFY' ) ) {
            return;
        }
        $button_customizer_settings = [];
        $this->button_customizer_settings( $button_customizer_settings );
        /**
         * Section: Button Section
         */
        \LOGINFY::createSection( $this->prefix, [
            'assign' => 'jlt_loginfy_customizer_login_form_button_section',
            'title'  => __( 'Button', 'loginfy' ),
            'fields' => $button_customizer_settings,
        ] );
    }

}
