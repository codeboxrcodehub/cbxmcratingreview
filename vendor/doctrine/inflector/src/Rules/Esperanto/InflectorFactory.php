<?php

declare (strict_types=1);
namespace CBXMCRatingReviewScoped\Doctrine\Inflector\Rules\Esperanto;

use CBXMCRatingReviewScoped\Doctrine\Inflector\GenericLanguageInflectorFactory;
use CBXMCRatingReviewScoped\Doctrine\Inflector\Rules\Ruleset;
final class InflectorFactory extends GenericLanguageInflectorFactory
{
    protected function getSingularRuleset(): Ruleset
    {
        return Rules::getSingularRuleset();
    }
    protected function getPluralRuleset(): Ruleset
    {
        return Rules::getPluralRuleset();
    }
}
