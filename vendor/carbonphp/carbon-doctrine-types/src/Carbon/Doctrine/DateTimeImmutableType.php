<?php

declare (strict_types=1);
namespace CBXMCRatingReviewScoped\Carbon\Doctrine;

use CBXMCRatingReviewScoped\Carbon\CarbonImmutable;
use DateTimeImmutable;
use CBXMCRatingReviewScoped\Doctrine\DBAL\Platforms\AbstractPlatform;
use CBXMCRatingReviewScoped\Doctrine\DBAL\Types\VarDateTimeImmutableType;
class DateTimeImmutableType extends VarDateTimeImmutableType implements CarbonDoctrineType
{
    /** @use CarbonTypeConverter<CarbonImmutable> */
    use CarbonTypeConverter;
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?CarbonImmutable
    {
        return $this->doConvertToPHPValue($value);
    }
    /**
     * @return class-string<CarbonImmutable>
     */
    protected function getCarbonClassName(): string
    {
        return CarbonImmutable::class;
    }
}
