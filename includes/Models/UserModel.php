<?php

namespace CBX\MCRatingReview\Models;

use CBX\MCRatingReview\Helpers\CBXMCRatingReviewHelper;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class UserModel
 *
 * @since 1.0.0
 */
class UserModel extends Eloquent {
	public $timestamps = false;
	protected $guarded = [];
	protected $table = 'users';

	protected $hidden = [ 'user_pass' ];

	public function __construct() {
		CBXMCRatingReviewHelper::load_orm();
	}

}//end Class UserModel