<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: accordion
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'LOGINFY_Field_accordion' ) ) {
  class LOGINFY_Field_accordion extends LOGINFY_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $unallows = array( 'accordion' );

      echo $this->field_before();

      echo '<div class="loginfy-accordion-items" data-depend-id="'. esc_attr( $this->field['id'] ) .'">';

      foreach ( $this->field['accordions'] as $key => $accordion ) {

        echo '<div class="loginfy-accordion-item">';

          $icon = ( ! empty( $accordion['icon'] ) ) ? 'loginfy--icon '. $accordion['icon'] : 'loginfy-accordion-icon fas fa-angle-right';

          echo '<h4 class="loginfy-accordion-title">';
          echo '<i class="'. esc_attr( $icon ) .'"></i>';
          echo esc_html( $accordion['title'] );
          echo '</h4>';

          echo '<div class="loginfy-accordion-content">';

          foreach ( $accordion['fields'] as $field ) {

            if ( in_array( $field['type'], $unallows ) ) { $field['_notice'] = true; }

            $field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
            $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
            $field_value   = ( isset( $this->value[$field_id] ) ) ? $this->value[$field_id] : $field_default;
            $unique_id     = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']' : $this->field['id'];

            LOGINFY::field( $field, $field_value, $unique_id, 'field/accordion' );

          }

          echo '</div>';

        echo '</div>';

      }

      echo '</div>';

      echo $this->field_after();

    }

  }
}
