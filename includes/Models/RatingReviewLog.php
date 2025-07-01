<?php
namespace CBX\MCRatingReview\Models;
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Rating log model
 */
class RatingReviewLog extends Eloquent {

	public $timestamps = false;
	protected $table = 'cbxmcratingreview_log';
	protected $appends = [ 'formatted_create_date', 'formatted_update_date', 'permalink', 'editlink' ];
	protected $fillable = [
		'form_id',
		'post_id',
		'post_type',
		'user_id',
		'score',
		'headline',
		'comment',
		'ratings',
		'questions',
		'attachment',
		'extraparams',
		'status',
		'mod_by',
		'date_created',
		'date_modified'
	];

	public function __construct() {
		CBXMCRatingReviewHelper::load_orm();
	}

	/**
	 * delete the form
	 *
	 * @return bool|null
	 */
	public function delete() {
		$review = $this->toArray();
		do_action( 'cbxmcratingreview_review_delete_before', $review );

		$delete = parent::delete();
		if ( $delete ) {
			do_action( 'cbxmcratingreview_review_delete_after', $review );
		} else {
			do_action( 'cbxmcratingreview_review_delete_failed', $review );
		}

		return $delete;
	}// end method delete

	/**
	 * Get Extra fields data
	 *
	 * @return array
	 */
	public function getExtraParamsAttribute() {
		if ( isset( $this->attributes['extraparams'] ) && ! is_null( $this->attributes['extraparams'] ) ) {
			return unserialize( $this->attributes['extraparams'] );
		} else {
			return [];
		}
	}//end method getExtrafieldsAttribute

	/**
	 * Get Extra fields data
	 *
	 * @return array
	 */
	public function getAttachmentAttribute() {
		if ( isset( $this->attributes['attachment'] ) && ! is_null( $this->attributes['attachment'] ) ) {
			return unserialize( $this->attributes['attachment'] );
		} else {
			return [];
		}
	}//end method getExtrafieldsAttribute

	/**
	 * get post edit link
	 *
	 */
	public function getEditlinkAttribute() {
		if ( ! isset( $this->attributes['post_id'] ) ) {
			return '';
		}

		return get_edit_post_link( $this->attributes['post_id'] );
	}


	/**
	 * Get Ratings data
	 *
	 * @return array
	 */
	public function getRatingsAttribute() {
		if ( isset( $this->attributes['ratings'] ) && ! is_null( $this->attributes['ratings'] ) ) {
			return unserialize( $this->attributes['ratings'] );
		} else {
			return [];
		}
	}//end method getRatingsAttribute

	/**
	 * Get Questions data
	 *
	 * @return array
	 */
	public function getQuestionsAttribute() {
		if ( isset( $this->attributes['questions'] ) && ! is_null( $this->attributes['questions'] ) ) {
			return unserialize( $this->attributes['questions'] );
		} else {
			return [];
		}
	}//end method getQuestionsAttribute

	/**
	 * get formatted create date
	 *
	 * @return string
	 */
	public function getFormattedCreateDateAttribute() {
		if ( ! isset( $this->attributes['id'] ) ) {
			return '';
		}
		if ( ! isset( $this->attributes['date_created'] ) ) {
			return '';
		}

		$format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );

		return date_i18n( $format, strtotime( $this->attributes['date_created'] ) );
	}//end method getFormattedCreateDateAttribute

	/**
	 * get formatted create date
	 *
	 * @return string
	 */
	public function getFormattedUpdateDateAttribute() {
		if ( ! isset( $this->attributes['id'] ) ) {
			return '';
		}
		if ( ! isset( $this->attributes['date_modified'] ) ) {
			return '';
		}

		$format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );

		return date_i18n( $format, strtotime( $this->attributes['date_modified'] ) );
	}//end method getFormattedUpdateDateAttribute


	/**
	 * get permalink
	 *
	 */
	public function getPermalinkAttribute() {
		if ( ! isset( $this->attributes['post_id'] ) ) {
			return '';
		}

		return get_permalink( $this->attributes['post_id'] );
	}//end method getPermalinkAttribute

	/**
	 * Relation between users table
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {
		return $this->belongsTo( UserModel::class, "user_id", "ID" );
	}

	/**
	 * Relation between users table
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function modUser() {
		return $this->belongsTo( UserModel::class, "mod_by", "ID" );
	}

	/**
	 * Relation between posts table
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function post() {
		return $this->belongsTo( PostModel::class, "post_id", "ID" );
	}

	/**
	 * Relation between users table
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function form() {
		return $this->belongsTo( RatingReviewForm::class, "form_id", "id" );
	}
}//end class RatingReviewLog