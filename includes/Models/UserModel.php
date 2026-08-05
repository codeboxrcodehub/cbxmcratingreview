<?php
namespace CBXMCRatingReview\Models;
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBXMCRatingReview\Helpers\CBXMCRatingReviewHelper;
use CBXMCRatingReviewScoped\Illuminate\Database\Eloquent\Model as Eloquent;

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