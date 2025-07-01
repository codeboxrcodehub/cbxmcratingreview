<?php
namespace CBX\MCRatingReview\Models;
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Rating form model
 */
class RatingReviewForm extends Eloquent {
	public $timestamps = false;
	protected $table = 'cbxmcratingreview_form';
	protected $fillable = [
		'name',
		'status',
		'custom_criteria',
		'custom_question',
		'extrafields'
	];
	
	/**
	 * delete the form
	 *
	 * @return bool|null
	 */
	public function delete() {
		$form = $this->toArray();
		do_action( 'cbxmcratingreview_form_delete_before', $form );

		$delete = parent::delete();
		if ( $delete ) {
			do_action( 'cbxmcratingreview_form_delete_after', $form );
		} else {
			do_action( 'cbxmcratingreview_form_delete_failed', $form );
		}

		return $delete;
	}// end method delete

	/**
	 * Get Custom Criteria data
	 *
	 * @return array
	 */
	public function getCustomCriteriaAttribute() {
		if ( isset( $this->attributes['custom_criteria'] ) && ! is_null( $this->attributes['custom_criteria'] ) ) {
			return unserialize( $this->attributes['custom_criteria'] );
		} else {
			return [];
		}
	}//end method getCustomCriteriaAttribute

	/**
	 * Get Custom Question data
	 *
	 * @return array
	 */
	public function getCustomQuestionAttribute() {
		if ( isset( $this->attributes['custom_question'] ) && ! is_null( $this->attributes['custom_question'] ) ) {
			return unserialize( $this->attributes['custom_question'] );
		} else {
			return [];
		}
	}//end method getCustomQuestionAttribute

	/**
	 * Get Extra fields data
	 *
	 * @return array
	 */
	public function getExtrafieldsAttribute() {
		if ( isset( $this->attributes['extrafields'] ) && ! is_null( $this->attributes['extrafields'] ) ) {
			return unserialize( $this->attributes['extrafields'] );
		} else {
			return [];
		}
	}//end method getExtrafieldsAttribute
}//end class RatingReviewForm