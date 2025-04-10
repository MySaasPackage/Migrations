<?php

declare(strict_types=1);

namespace MySaasPackage\Migrations;

use PDO;

class EnsureMigrator
{
    public function __construct(
        protected readonly PDO $pdo,
        protected readonly string $migrationsTable
    ) {
    }

    public function ensureMigrationsTableExists(): void
    {
        [$schema] = explode('.', $this->migrationsTable);

        $sql = <<<SQL
        CREATE SCHEMA IF NOT EXISTS {$schema};

        SET search_path = {$schema};

        CREATE TABLE IF NOT EXISTS {$this->migrationsTable} (
            id SERIAL PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            migrated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
SQL;
        $this->pdo->exec($sql);
    }
}
