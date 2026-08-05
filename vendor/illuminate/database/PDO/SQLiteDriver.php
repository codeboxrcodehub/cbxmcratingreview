<?php

namespace CBXMCRatingReviewScoped\Illuminate\Database\PDO;

use CBXMCRatingReviewScoped\Doctrine\DBAL\Driver\AbstractSQLiteDriver;
use CBXMCRatingReviewScoped\Illuminate\Database\PDO\Concerns\ConnectsToDatabase;
class SQLiteDriver extends AbstractSQLiteDriver
{
    use ConnectsToDatabase;
}
