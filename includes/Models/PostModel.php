<?php
namespace CBXMCRatingReview\Models;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBXMCRatingReview\Helpers\CBXMCRatingReviewHelper;
use CBXMCRatingReviewScoped\Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class PostModel
 *
 * @since 1.0.0
 */
class PostModel extends Eloquent {
	public $timestamps = false;
	protected $guarded = [];
	protected $table = 'posts';

	public function __construct() {
		CBXMCRatingReviewHelper::load_orm();
	}
}//end Class PostModel