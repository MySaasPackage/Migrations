<?php

declare(strict_types=1);

namespace MySaasPackage\Migrations;

use PDO;

class UpwardMigrator
{
    public function __construct(
        protected readonly PDO $pdo,
        protected readonly string $migrationsTable,
        protected readonly string $migrationsDir,
    ) {
    }

    public function isMigrated(string $migration): bool
    {
        $stmt = $this->pdo->prepare(sprintf('SELECT * FROM %s WHERE migration = :migration', $this->migrationsTable));
        $stmt->execute([
            'migration' => $migration,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function migrate(MigrationContext $context): void
    {
        $files = glob(sprintf('%s/*.php', $this->migrationsDir));
        sort($files);

        foreach ($files as $file) {
            $this->migrateSingle($file, $context);
        }
    }

    public function migrateSingle(string $file, MigrationContext $context): void
    {
        $migration = basename($file, '.php');
        if ($this->isMigrated($migration)) {
            return;
        }

        ['up' => $up] = require $file;
        $up($this->pdo, $context);

        $stmt = $this->pdo->prepare(sprintf('INSERT INTO %s (migration) VALUES (:migration)', $this->migrationsTable));
        $stmt->execute([
            'migration' => $migration,
        ]);
    }
}
