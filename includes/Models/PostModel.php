<?php

namespace CBX\MCRatingReview\Models;

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;
use Illuminate\Database\Eloquent\Model as Eloquent;

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