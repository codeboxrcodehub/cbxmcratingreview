<?php
namespace CBX\MCRatingReview\Models;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Illuminate\Database\Eloquent\Model as Eloquent;

class Migrations extends Eloquent {

	public $timestamps = false;
	protected $table = 'cbxmigrations';
	protected $guarded = [];
}//end class Migrations