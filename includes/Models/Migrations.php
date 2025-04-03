<?php

namespace CBX\MCRatingReview\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Migrations extends Eloquent {

	public $timestamps = false;
	protected $table = 'cbxmigrations';
	protected $guarded = [];
}//end class Migrations