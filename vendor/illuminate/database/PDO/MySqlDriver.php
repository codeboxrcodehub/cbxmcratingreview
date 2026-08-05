<?php

namespace CBXMCRatingReviewScoped\Illuminate\Database\PDO;

use CBXMCRatingReviewScoped\Doctrine\DBAL\Driver\AbstractMySQLDriver;
use CBXMCRatingReviewScoped\Illuminate\Database\PDO\Concerns\ConnectsToDatabase;
class MySqlDriver extends AbstractMySQLDriver
{
    use ConnectsToDatabase;
}
