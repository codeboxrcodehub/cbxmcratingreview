<?php

namespace CBXMCRatingReviewScoped\Illuminate\Support;

use CBXMCRatingReviewScoped\Carbon\Carbon as BaseCarbon;
use CBXMCRatingReviewScoped\Carbon\CarbonImmutable as BaseCarbonImmutable;
class Carbon extends BaseCarbon
{
    /**
     * {@inheritdoc}
     */
    public static function setTestNow($testNow = null)
    {
        BaseCarbon::setTestNow($testNow);
        BaseCarbonImmutable::setTestNow($testNow);
    }
}
