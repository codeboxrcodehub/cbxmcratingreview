<?php

namespace CBXMCRatingReviewScoped\Illuminate\Database\Eloquent\Relations;

use CBXMCRatingReviewScoped\Illuminate\Database\Eloquent\Model;
use CBXMCRatingReviewScoped\Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;
class Pivot extends Model
{
    use AsPivot;
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = \false;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
