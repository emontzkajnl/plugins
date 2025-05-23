<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This function displays the fill in the blank question
 *
 * @params $id The ID of the multiple choice question
 * @params $question The question that is being edited.
 * @params @answers The array that contains the answers to the question.
 * @since  4.4.0
 */
function qmn_fill_blank_display( $id, $question, $answers ) {
	global $mlwQuizMasterNext, $allowedposttags;
	$allowedposttags['input']    = array(
		'autocomplete' => true,
		'class'        => true,
		'id'           => true,
		'height'       => true,
		'min'          => true,
		'max'          => true,
		'minlength'    => true,
		'maxlength'    => true,
		'name'         => true,
		'pattern'      => true,
		'placeholder'  => true,
		'readonly'     => true,
		'required'     => true,
		'size'         => true,
		'step'         => true,
		'type'         => true,
		'value'        => true,
		'width'        => true,
	);
	$required                    = $mlwQuizMasterNext->pluginHelper->get_question_setting( $id, 'required' );
	$autofill                    = $mlwQuizMasterNext->pluginHelper->get_question_setting( $id, 'autofill' );
	$limit_text                  = $mlwQuizMasterNext->pluginHelper->get_question_setting( $id, 'limit_text' );
	$min_fill_text               = $mlwQuizMasterNext->pluginHelper->get_question_setting( $id, 'min_text_length' );
	$autofill_att                = $autofill ? "autocomplete='off' " : '';
	$limit_text_att              = $limit_text ? "maxlength='" . $limit_text . "' " : '';
	$min_fill_text_att           = $min_fill_text ? "minlength='" . $min_fill_text . "' " : '';
	if ( 0 == $required ) {
		$mlw_require_class = 'mlwRequiredText';
	} else {
		$mlw_require_class = '';
	}
	$input_text = '<input ' . $min_fill_text_att . $autofill_att . $limit_text_att . " type='text' class='qmn_fill_blank $mlw_require_class' name='question" . $id . "[]' />";
	$input_text = apply_filters( 'qsm_fill_in_blanks_input_after', $input_text, $id, $question, $answers, $mlw_require_class );
	if ( ! empty( $question ) ) {
		$question = $mlwQuizMasterNext->pluginHelper->qsm_language_support( htmlspecialchars_decode( html_entity_decode( $question, ENT_HTML5 ), ENT_QUOTES ), "question-description-{$id}", "QSM Questions" );
	}
	$new_question_title = $mlwQuizMasterNext->pluginHelper->get_question_setting( $id, 'question_title' );
	if ( strpos( $question, '%BLANK%' ) !== false ) {
		$question = str_replace( '%BLANK%', $input_text, htmlspecialchars_decode( $question, ENT_QUOTES ) );
		qsm_question_title_func( $question, 'fill_in_blank', $new_question_title, $id );
		$question = do_shortcode( $question );
	} else {
		qsm_question_title_func( $question, 'fill_in_blank', $new_question_title, $id );
	}
	echo apply_filters( 'qmn_fill_blank_display_front', '', $id, $question, $answers );
}

/**
 * This function determines how the fill in the blank question is graded.
 *
 * @params $id The ID of the multiple choice question
 * @params $question The question that is being edited.
 * @params @answers The array that contains the answers to the question.
 * @return $return_array Returns the graded question to the results page
 * @since  4.4.0
 */
function qmn_fill_blank_review( $id, $question, $answers ) {
	global $mlwQuizMasterNext;
	if ( ! empty( $question ) ) {
		$question = $mlwQuizMasterNext->pluginHelper->qsm_language_support( htmlspecialchars_decode( html_entity_decode( $question, ENT_HTML5 ), ENT_QUOTES ), "question-description-{$id}", "QSM Questions" );
	}
	$case_sensitive = $mlwQuizMasterNext->pluginHelper->get_question_setting( $id, 'case_sensitive' );
	$current_question  = new QSM_Question_Review_Fill_In_Blanks( $id, $question, $answers );
	$user_text_array                   = $current_question->get_user_answer();
	$correct_text_array                = $current_question->get_correct_answer();
	$return_array['user_text']         = ! empty( $user_text_array ) ? implode( ', ', $user_text_array ) : '' ;
	$return_array['correct_text']      = ! empty( $correct_text_array ) ? implode( ', ', $correct_text_array ) : '';
	$return_array['correct']           = $current_question->get_answer_status();
	$return_array['points']            = $current_question->get_points();
	$return_array['user_compare_text'] = ! empty( $user_text_array ) ? implode( '=====', $user_text_array ) : '' ;
	$return_array['user_answer']       = $user_text_array;
	$return_array['correct_answer']    = $correct_text_array ;
	$return_array['case_sensitive']    = $case_sensitive ;
	if ( $current_question->get_question_text() ) {
		$return_array['question_text'] = $current_question->get_question_text();
	}
	/**
	 * Hook to filter answers array
	 */
	return apply_filters( 'qmn_fill_blank_review', $return_array, $answers );
}