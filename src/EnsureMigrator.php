<?php

declare(strict_types=1);

namespace MySaasPackage\Migrations;

use PDO;

class EnsureMigrator
{
    public function __construct(
        protected readonly PDO $pdo
    ) {
    }

    public function ensureMigrationsTableExists(): void
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    migrated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
SQL;
        $this->pdo->exec($sql);
    }
}
