<?php
namespace CBX\MCRatingReview\Helpers;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class CBXMCRatingReviewQuestionHelper
 */
class CBXMCRatingReviewQuestionHelper {
	/**
	 * Text (Single line input) field form display
	 *
	 * @param int $question_index
	 * @param array $question
	 * @param array $stored_values = []
	 *
	 * @return string
	 */
	public static function public_display_text_field( $question_index = 0, $question = [], $stored_values = '' ) {
		$question_index = intval( $question_index );
		$required       = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		/* translators: %d: Question ID  */
		$title       = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$placeholder = isset( $question['placeholder'] ) ? esc_attr( $question['placeholder'] ) : '';

		$required_text      = ( $required ) ? ' required ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';

		//$user_answer = isset($question['user_answer'])? $question['user_answer']: '';


		$output = '<label class="cbxmcratingreview_q_field_label cbxmcratingreview_q_field_label_text" for="cbxmcratingreview_q_field_' . $question_index . '">' . $title . '</label>';
		$output .= '<input ' . $required_text . $required_data_text . ' id="cbxmcratingreview_q_field_' . $question_index . '" class="regular-text cbxmcratingreview_q_field cbxmcratingreview_q_field_text" type="text" name="cbxmcratingreview_ratingForm[questions][' . $question_index . ']" placeholder="' . $placeholder . '" value="' . $stored_values . '" />';

		return $output;
	}//end method  admin_display_text_field

	/**
	 * Text (Single line input) field answer display
	 *
	 * @param int $question_index
	 * @param array $question
	 * @param array $stored_values = []
	 *
	 * @return string
	 */
	public static function answer_display_text_field( $question_index = 0, $question = [], $stored_values = '' ) {
		$question_index = intval( $question_index );
		/* translators: %d: Question ID  */
		$title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );

		$output = '<p class="cbxmcratingreview_q_field_answer cbxmcratingreview_q_field_answer_text" for="cbxmcratingreview_q_field_' . $question_index . '"><strong>' . esc_html__( 'Q:', 'cbxmcratingreview' ) . '</strong>' . ' ' . $title . '</p>';
		$output .= '<p>' . $stored_values . '</p>';

		return $output;
	}//end method  answer_display_text_field

	/**
	 * Textarea Field Display
	 *
	 * @param int $question_index
	 * @param array $question
	 * @param string $stored_values
	 *
	 * @return string
	 */
	public static function public_display_textarea_field( $question_index = 0, $question = [], $stored_values = '' ) {
		$question_index = intval( $question_index );
		$required       = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		/* translators: %d: Question ID  */
		$title         = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$placeholder   = isset( $question['placeholder'] ) ? esc_attr( $question['placeholder'] ) : '';
		$required_text = ( $required ) ? ' required ' : '';

		$output = '<label class="cbxmcratingreview_q_field_label cbxmcratingreview_q_field_label_textarea" for="cbxmcratingreview_q_field_' . $question_index . '">' . $title . '</label>';
		$output .= '<textarea ' . $required_text . ' rows="5" cols="20" id="cbxmcratingreview_q_field_' . $question_index . '" class="regular-text cbxmcratingreview_q_field cbxmcratingreview_q_field_textarea" type="text" name="cbxmcratingreview_ratingForm[questions][' . $question_index . ']" placeholder="' . $placeholder . '" />' . $stored_values . '</textarea>';

		return $output;
	}//end method  admin_display_textarea_field

	/**
	 * Textarea answer Display
	 *
	 * @param int $question_index
	 * @param array $question
	 * @param string $stored_values
	 *
	 * @return string
	 */
	public static function answer_display_textarea_field( $question_index = 0, $question = [], $stored_values = '' ) {
		$question_index = intval( $question_index );
		/* translators: %d: Question ID  */
		$title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );

		$output = '<p class="cbxmcratingreview_q_field_answer cbxmcratingreview_q_field_answer_textarea" for="cbxmcratingreview_q_field_' . $question_index . '"><strong>' . esc_html__( 'Q:', 'cbxmcratingreview' ) . '</strong>' . ' ' . $title . '</p>';
		//$output .= '<textarea ' . $required_text . ' rows="5" cols="20" id="cbxmcratingreview_q_field_' . $question_index . '" class="regular-text cbxmcratingreview_q_field cbxmcratingreview_q_field_textarea" type="text" name="cbxmcratingreview_ratingForm[questions][' . $question_index . ']" placeholder="' . $placeholder . '" />' . $stored_values . '</textarea>';

		$output .= $stored_values;

		return $output;
	}//end method  answer_display_textarea_field

	/**
	 * Number field display
	 *
	 * @param int $question_index
	 * @param array $question
	 * @param array $stored_values
	 *
	 * @return string
	 */
	public static function public_display_number_field( $question_index = 0, $question = [], $stored_values = '' ) {
		$question_index = intval( $question_index );
		$required       = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		/* translators: %d: Question ID  */
		$title              = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), absint( $question_index ) );
		$required_text      = ( $required ) ? ' required ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';


		$min  = isset( $question['min'] ) ? floatval( $question['min'] ) : 0;
		$max  = isset( $question['max'] ) ? floatval( $question['max'] ) : 100;
		$step = isset( $question['step'] ) ? floatval( $question['step'] ) : 1;

		$output = '<label class="cbxmcratingreview_q_field_label cbxmcratingreview_q_field_label_number" for="cbxmcratingreview_q_field_' . $question_index . '">' . $title . '</label>';
		$output .= '<input id="cbxmcratingreview_q_field_' . $question_index . '" class="regular-text cbxmcratingreview_q_field cbxmcratingreview_q_field_number" type="number" name="cbxmcratingreview_ratingForm[questions][' . $question_index . ']"  value="' . $stored_values . '" min="' . $min . '" max="' . $max . '" step="' . $step . '" ' . $required_text . $required_data_text . ' />';

		return $output;
	}//end method  admin_display_number_field

	/**
	 * Number answer display
	 *
	 * @param int $question_index
	 * @param array $question
	 * @param array $stored_values
	 *
	 * @return string
	 */
	public static function answer_display_number_field( $question_index = 0, $question = [], $stored_values = '' ) {
		$question_index = intval( $question_index );
		/* translators: %d: Question ID  */
		$title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), absint( $question_index ) );

		$output = '<p class="cbxmcratingreview_q_field_answer cbxmcratingreview_q_field_answer_number" for="cbxmcratingreview_q_field_' . $question_index . '"><strong>' . esc_html__( 'Q:', 'cbxmcratingreview' ) . '</strong>' . ' ' . $title . ' : ' . $stored_values . '</p>';

		return $output;
	}//end method  answer_display_number_field

	/**
	 * Checkbox field display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function public_display_checkbox_field( $question_index = 0, $question = [], $stored_values = 0 ) {
		$question_index = intval( $question_index );
		$required       = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		/* translators: %d: Question ID  */
		$title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), absint( $question_index ) );
		//$placeholder = isset($question['placeholder']) ? esc_attr($question['placeholder']) : '';
		$required_text      = ( $required ) ? ' required ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';

		$stored_values = intval( $stored_values );

		$output = '<div class="checkbox_field magic_checkbox_field">';
		$output .= '<input class="magic-checkbox cbxmcratingreview_q_field cbxmcratingreview_q_field_checkbox" id="cbxmcratingreview_q_field_' . $question_index . '" ' . $required_text . $required_data_text . ' name="cbxmcratingreview_ratingForm[questions][' . $question_index . ']" type="checkbox" ' . checked( $stored_values, 1,
				false ) . ' value="1"  />';

		$output .= '<label class="cbxmcratingreview_q_field_label cbxmcratingreview_q_field_label_checkbox" for="cbxmcratingreview_q_field_' . $question_index . '">' . $title . '</label>';
		$output .= '</div>';

		return $output;
	}//end method admin_display_checkbox_field


	/**
	 * Checkbox answer display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function answer_display_checkbox_field( $question_index = 0, $question = [], $stored_values = 0 ) {
		$question_index = intval( $question_index );
		/* translators: %d: Question ID  */
		$title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );

		$stored_values = intval( $stored_values );

		$output = '<p class="cbxmcratingreview_q_field_answer cbxmcratingreview_q_field_answer_checkbox" for="cbxmcratingreview_q_field_' . $question_index . '"><strong>' . esc_html__( 'Q:', 'cbxmcratingreview' ) . '</strong>' . ' ' . $title . ' : ' . ( ( $stored_values == 1 ) ? esc_html__( 'Yes', 'cbxmcratingreview' ) : esc_html__( 'No', 'cbxmcratingreview' ) ) . '</p>';

		return $output;
	}//end method answer_display_checkbox_field

	/**
	 * Multi checkbox field display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function public_display_multicheckbox_field( $question_index = 0, $question = [], $stored_values = [] ) {
		$question_index = intval( $question_index );
		$required       = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		/* translators: %d: Question ID  */
		$title   = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$options = isset( $question['options'] ) ? (array) $question['options'] : [];

		$required_minlength_text = ( $required ) ? ' data-rule-cbxmcratingreview_multicheckbox="1" ' : '';

		$stored_values = maybe_unserialize( $stored_values );
		if ( ! is_array( $stored_values ) ) {
			$stored_values = [];
		}


		$stored_values = array_keys( $stored_values );

		$output = '<label class="cbxmcratingreview_q_field_label cbxmcratingreview_q_field_label_multicheckbox">' . $title . '</label>';
		$output .= '<div class="checkbox_fields magic_checkbox_fields cbxmcratingreview_q_field_label_multicheckboxes">';
		foreach ( $options as $option_index => $option ) {
			$label = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );

			$stored_value = in_array( $option_index, $stored_values ) ? 1 : 0;

			$output .= '<div class="checkbox_field magic_checkbox_field">';
			$output .= '<input class="magic-checkbox cbxmcratingreview_q_field_option cbxmcratingreview_q_field_option_multicheckbox" id="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '"  name="cbxmcratingreview_ratingForm[questions][' . $question_index . '][' . $option_index . ']" type="checkbox" ' . checked( $stored_value,
					1, false ) . ' value="1" ' . $required_minlength_text . ' />';

			$output .= '<label class="cbxmcratingreview_q_field_label_option cbxmcratingreview_q_field_label_option_multicheckbox" for="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '">' . $label . '</label>';

			$output .= '</div>';
		}                    //end for each option

		$output .= '</div>'; //.cbxmcratingreview_q_field_label_multicheckboxes

		return $output;
	}//end method admin_display_multicheckbox_field


	/**
	 * Multi checkbox answer display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function answer_display_multicheckbox_field( $question_index = 0, $question = [], $stored_values = [] ) {
		$question_index = intval( $question_index );
		/* translators: %d: Question ID  */
		$title   = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$options = isset( $question['options'] ) ? (array) $question['options'] : [];


		$stored_values = maybe_unserialize( $stored_values );


		if ( ! is_array( $stored_values ) ) {
			$stored_values = [];
		}
		$stored_values = array_keys( $stored_values );

		$output = '<p class="cbxmcratingreview_q_field_answer cbxmcratingreview_q_field_answer_multicheckbox"><strong>' . esc_html__( 'Q:', 'cbxmcratingreview' ) . '</strong>' . ' ' . $title . '</p>';
		$output .= '<div class="cbxmcratingreview_q_field_label_multicheckboxes">';

		$answer_output = '';
		foreach ( $options as $option_index => $option ) {
			$label = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );

			$stored_value = in_array( $option_index, $stored_values ) ? 1 : 0;

			if ( $stored_value == 1 ) {
				if ( $answer_output != '' ) {
					$answer_output .= ', ';
				}
				$answer_output .= $label;
			}
		}//end for each option

		if ( $answer_output != '' ) {
			$output .= '<p class="cbxmcratingreview_q_field_answer_option cbxmcratingreview_q_field_answer_option_multicheckbox" >' . $answer_output . '</p>';
		}
		$output .= '</div>'; //.cbxmcratingreview_q_field_label_multicheckboxes

		return $output;
	}//end method answer_display_multicheckbox_field

	/**
	 * Radio Field display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function public_display_radio_field( $question_index = 0, $question = [], $stored_values = '' ) {
		$question_index = absint( $question_index );
		$required       = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		/* translators: %d: Question ID  */
		$title              = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$options            = isset( $question['options'] ) ? (array) $question['options'] : [];
		$required_text      = ( $required ) ? ' required ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';

		$output = '<p class="cbxmcratingreview_q_field_label cbxmcratingreview_q_field_label_radio">' . $title . '</p>';
		$output .= '<div class="radio_fields magic_radio_fields cbxmcratingreview_q_field_label_radios">';
		foreach ( $options as $option_index => $option ) {
			$label = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );

			$output .= '<div class="magic-radio-field">';
			$output .= '<input ' . $required_text . $required_data_text . '  class="magic-radio cbxmcratingreview_q_field_option cbxmcratingreview_q_field_option_radio" id="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '"  name="cbxmcratingreview_ratingForm[questions][' . $question_index . ']" type="radio" ' . checked( $stored_values,
					$option_index, false ) . ' value="' . esc_attr( $option_index ) . '" class="form-checkbox" />';
			$output .= '<label class="cbxmcratingreview_q_field_label_option cbxmcratingreview_q_field_label_option_radio" for="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '">' . $label . '</label>';
			$output .= '</div>';
		}                    //end for each option
		$output .= '</div>'; //.cbxmcratingreview_q_field_label_radios

		return $output;


	}//end admin_display_radio_field

	/**
	 * Radio answer display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function answer_display_radio_field( $question_index = 0, $question = [], $stored_values = '' ) {
		$question_index = intval( $question_index );
		/* translators: %d: Question ID  */
		$title   = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$options = isset( $question['options'] ) ? (array) $question['options'] : [];

		$output = '<p class="cbxmcratingreview_q_field_answer cbxmcratingreview_q_field_answer_radio"><strong>' . esc_html__( 'Q:', 'cbxmcratingreview' ) . '</strong>' . ' ' . $title . '</p>';
		$output .= '<div class="cbxmcratingreview_q_field_answer_radios">';
		foreach ( $options as $option_index => $option ) {
			$label = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );

			if ( intval( $stored_values ) === $option_index ) {
				//$output       .= '<p class="cbxmcratingreview_q_field_answer_option cbxmcratingreview_q_field_label_option_select" for="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '">' . $label . ' : '.((intval($stored_values) === $option_index)? esc_html__('Yes', 'cbxmcratingreview'): esc_html__('No', 'cbxmcratingreview')).'</p>';
				$output .= '<p class="cbxmcratingreview_q_field_answer_option cbxmcratingreview_q_field_label_option_select" for="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '">' . $label . '</p>';
			}
		}                    //end for each option
		$output .= '</div>'; //.cbxmcratingreview_q_field_label_radios

		return $output;
	}//end answer_display_radio_field

	/**
	 * Select Field display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function public_display_select_field( $question_index = 0, $question = [], $stored_values = '' ) {
		$question_index = absint( $question_index );
		$required       = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		$multiple       = isset( $question['multiple'] ) ? intval( $question['multiple'] ) : 0;
		/* translators: %d: Question ID  */
		$title              = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$options            = isset( $question['options'] ) ? (array) $question['options'] : [];
		$required_text      = ( $required ) ? ' required ' : '';
		$multiple_text      = ( $multiple ) ? ' multiple ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';

		$name = 'cbxmcratingreview_ratingForm[questions][' . $question_index . ']';
		if ( $multiple ) {
			$name .= '[]';
		}


		if ( $multiple ) {
			$stored_values = maybe_unserialize( $stored_values );
			if ( ! is_array( $stored_values ) ) {
				$stored_values = [];
			}
			$stored_values = array_values( $stored_values );
		}


		$output = '<label class="cbxmcratingreview_q_field_label cbxmcratingreview_q_field_label_select" for="cbxmcratingreview_q_field_' . $question_index . '">' . $title . '</label>';
		$output .= '<select name="' . $name . '" ' . $multiple_text . '  ' . $required_text . $required_data_text . ' class="cbxmcratingreview_q_field cbxmcratingreview_q_field_select" id="cbxmcratingreview_q_field_' . $question_index . '">';
		$output .= '<option value="">' . esc_html__( 'Please select', 'cbxmcratingreview' ) . '</option>';
		foreach ( $options as $option_index => $option ) {
			$label = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );
			//$stored_value = isset( $stored_values[ $option_index ] ) ? intval( $stored_values[ $option_index ] ) : '';

			if ( $multiple ) {
				$stored_value = in_array( $option_index, $stored_values ) ? $option_index : '';

				$output .= '<option value="' . $option_index . '" ' . selected( $stored_value, $option_index, false ) . '">' . $label . '</option>';
			} else {
				$output .= '<option value="' . $option_index . '" ' . selected( $stored_values, $option_index, false ) . '">' . $label . '</option>';
			}


		}                       //end for each option
		$output .= '</select>'; //.cbxmcratingreview_q_field_label_select

		return $output;
	}//end method admin_display_select_field

	/**
	 * Select answer display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function answer_display_select_field( $question_index = 0, $question = [], $stored_values = '' ) {
		$question_index = absint( $question_index );
		$multiple       = isset( $question['multiple'] ) ? intval( $question['multiple'] ) : 0;
		/* translators: %d: Question ID  */
		$title   = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$options = isset( $question['options'] ) ? (array) $question['options'] : [];

		$name = 'cbxmcratingreview_ratingForm[questions][' . $question_index . ']';
		if ( $multiple ) {
			$name .= '[]';
		}


		if ( $multiple ) {
			$stored_values = maybe_unserialize( $stored_values );
			if ( ! is_array( $stored_values ) ) {
				$stored_values = [];
			}
			$stored_values = array_values( $stored_values );
		}


		$output = '<p class="cbxmcratingreview_q_field_answer cbxmcratingreview_q_field_answer_select" for="cbxmcratingreview_q_field_' . $question_index . '"><strong>' . esc_html__( 'Q:', 'cbxmcratingreview' ) . '</strong>' . ' ' . $title . '</p>';

		$output .= '<div class="cbxmcratingreview_q_field_label_selects">';

		$answer_output = '';
		foreach ( $options as $option_index => $option ) {
			$label = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );
			//$stored_value = isset( $stored_values[ $option_index ] ) ? intval( $stored_values[ $option_index ] ) : '';

			if ( $multiple ) {
				$stored_value = in_array( $option_index, $stored_values ) ? $option_index : '';

				if ( intval( $stored_value ) === $option_index ) {
					//$output       .= '<p class="cbxmcratingreview_q_field_answer_option cbxmcratingreview_q_field_answer_option_radio" for="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '">' . $label . '</p>';
					if ( $answer_output != '' ) {
						$answer_output .= ', ';
					}
					$answer_output .= $label;
				}

			} else {

				if ( intval( $stored_values ) === $option_index ) {
					$output .= '<p class="cbxmcratingreview_q_field_answer_option cbxmcratingreview_q_field_answer_option_select" for="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '">' . $label . '</p>';
				}


			}
		}//end for each option

		if ( $answer_output != '' ) {
			$output .= '<p class="cbxmcratingreview_q_field_answer_option cbxmcratingreview_q_field_answer_option_radio">' . $answer_output . '</p>';
		}

		$output .= '</div>';

		return $output;
	}//end method answer_display_select_field

	public static function arrayFilterRemoveEmpty( $var ) {
		if ( $var == '' ) {
			return false;
		} else {
			return true;
		}

	}
}//end method CBXMCRatingReviewQuestionHelper