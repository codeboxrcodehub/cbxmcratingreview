<?php

namespace CBXMCRatingReviewScoped\Illuminate\Database\PDO;

use CBXMCRatingReviewScoped\Doctrine\DBAL\Driver\AbstractPostgreSQLDriver;
use CBXMCRatingReviewScoped\Illuminate\Database\PDO\Concerns\ConnectsToDatabase;
class PostgresDriver extends AbstractPostgreSQLDriver
{
    use ConnectsToDatabase;
}
