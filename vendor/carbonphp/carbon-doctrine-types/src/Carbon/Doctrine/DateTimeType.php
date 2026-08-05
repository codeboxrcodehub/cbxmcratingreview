<?php

declare (strict_types=1);
namespace CBXMCRatingReviewScoped\Carbon\Doctrine;

use CBXMCRatingReviewScoped\Carbon\Carbon;
use DateTime;
use CBXMCRatingReviewScoped\Doctrine\DBAL\Platforms\AbstractPlatform;
use CBXMCRatingReviewScoped\Doctrine\DBAL\Types\VarDateTimeType;
class DateTimeType extends VarDateTimeType implements CarbonDoctrineType
{
    /** @use CarbonTypeConverter<Carbon> */
    use CarbonTypeConverter;
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Carbon
    {
        return $this->doConvertToPHPValue($value);
    }
}
