<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: checkbox
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'LOGINFY_Field_checkbox' ) ) {
  class LOGINFY_Field_checkbox extends LOGINFY_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args              = wp_parse_args( $this->field, array(
        'inline'         => false,
        'query_args'     => array(),
        'check_all'      => false,
        'check_all_text' => esc_html__( 'Check/Uncheck All' ),
      ) );

      $inline_class = ( $args['inline'] ) ? ' class="loginfy--inline-list"' : '';

      echo $this->field_before();

      if ( isset( $this->field['options'] ) ) {

        $value   = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );
        $options = $this->field['options'];
        $options = ( is_array( $options ) ) ? $options : array_filter( $this->field_data( $options, false, $args['query_args'] ) );

        if ( is_array( $options ) && ! empty( $options ) ) {

          echo '<ul'. $inline_class .'>';

          foreach ( $options as $option_key => $option_value ) {

            if ( is_array( $option_value ) && ! empty( $option_value ) ) {

              echo '<li>';
                echo '<ul>';
                  echo '<li><strong>'. esc_attr( $option_key ) .'</strong></li>';
                  foreach ( $option_value as $sub_key => $sub_value ) {
                    $checked = ( in_array( $sub_key, $value ) ) ? ' checked' : '';
                    echo '<li>';
                    echo '<label>';
                    echo '<input type="checkbox" name="'. esc_attr( $this->field_name( '[]' ) ) .'" value="'. esc_attr( $sub_key ) .'"'. $this->field_attributes() . esc_attr( $checked ) .'/>';
                    echo '<span class="loginfy--text">'. esc_attr( $sub_value ) .'</span>';
                    echo '</label>';
                    echo '</li>';
                  }
                echo '</ul>';
              echo '</li>';

            } else {

              $checked = ( in_array( $option_key, $value ) ) ? ' checked' : '';

              echo '<li>';
              echo '<label>';
              echo '<input type="checkbox" name="'. esc_attr( $this->field_name( '[]' ) ) .'" value="'. esc_attr( $option_key ) .'"'. $this->field_attributes() . esc_attr( $checked ) .'/>';
              echo '<span class="loginfy--text">'. esc_attr( $option_value ) .'</span>';
              echo '</label>';
              echo '</li>';

            }

          }

          echo '</ul>';

          if ( $args['check_all'] ) {
            echo '<div class="loginfy-checkbox-all">'. esc_html( $args['check_all_text'] ) .'</div>';
          }

        } else {

          echo ( ! empty( $this->field['empty_message'] ) ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No data available.', 'loginfy' );

        }

      } else {

        echo '<label class="loginfy-checkbox">';
        echo '<input type="hidden" name="'. esc_attr( $this->field_name() ) .'" value="'. $this->value .'" class="loginfy--input"'. $this->field_attributes() .'/>';
        echo '<input type="checkbox" name="_pseudo" class="loginfy--checkbox"'. esc_attr( checked( $this->value, 1, false ) ) . $this->field_attributes() .'/>';
        echo ( ! empty( $this->field['label'] ) ) ? '<span class="loginfy--text">'. esc_attr( $this->field['label'] ) .'</span>' : '';
        echo '</label>';

      }

      echo $this->field_after();

    }

  }
}
