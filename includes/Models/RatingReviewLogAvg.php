<?php
namespace CBX\MCRatingReview\Models;
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Avg rating log model
 */
class RatingReviewLogAvg extends Eloquent {

	public $timestamps = false;
	protected $table = 'cbxmcratingreview_log_avg';
	protected $appends = [ 'formatted_create_date', 'formatted_update_date', 'permalink', 'editlink' ];
	protected $fillable = [
		'form_id',
		'post_id',
		'post_type',
		'avg_rating',
		'total_count',
		'rating_stat',
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
		$form = $this->toArray();
		do_action( 'cbxmcratingreview_log_avg_delete_before', $this->id, $form );

		$delete = parent::delete();
		if ( $delete ) {
			do_action( 'cbxmcratingreview_log_avg_delete_after', $this->id, $form );
		} else {
			do_action( 'cbxmcratingreview_log_avg_delete_failed', $this->id, $form );
		}

		return $delete;
	}// end method delete


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
	 * Relation between posts table
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function post() {
		return $this->belongsTo( PostModel::class, "post_id", "ID" );
	}


	/**
	 * get permalink
	 *
	 */
	public function getPermalinkAttribute() {
		if ( ! isset( $this->attributes['post_id'] ) ) {
			return '';
		}

		return get_permalink( $this->attributes['post_id'] );
	}

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
	 * Relation between users table
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function form() {
		return $this->belongsTo( RatingReviewForm::class, "form_id", "id" );
	}
}//end class RatingReviewLogAvg