<?php

namespace CBXMCRatingReviewScoped\Rakit\Validation\Rules;

use CBXMCRatingReviewScoped\Rakit\Validation\Rule;
class Numeric extends Rule
{
    /** @var string */
    protected $message = "The :attribute must be numeric";
    /**
     * Check the $value is valid
     *
     * @param mixed $value
     * @return bool
     */
    public function check($value): bool
    {
        return is_numeric($value);
    }
}
