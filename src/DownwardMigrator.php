<?php

declare(strict_types=1);

namespace MySaasPackage\Migrations;

use PDO;

class DownwardMigrator
{
    public function __construct(
        protected readonly PDO $pdo,
        protected readonly string $migrationsTable,
        protected readonly string $migrationsDir,
    ) {
    }

    public function isNotMigrated(string $migration): bool
    {
        $stmt = $this->pdo->prepare(sprintf('SELECT * FROM %s WHERE migration = :migration', $this->migrationsTable));
        $stmt->execute([
            'migration' => $migration,
        ]);

        return 0 === $stmt->rowCount();
    }

    public function migrate(Context $context): void
    {
        $files = glob(sprintf('%s/*.php', $this->migrationsDir));
        rsort($files);

        foreach ($files as $file) {
            $this->migrateSingle($file, $context);
        }
    }

    public function migrateSingle(string $file, Context $context): void
    {
        $migration = basename($file, '.php');
        if ($this->isNotMigrated($migration)) {
            return;
        }

        ['down' => $down] = require $file;
        $down($this->pdo, $context);

        $stmt = $this->pdo->prepare(sprintf('DELETE FROM %s WHERE migration = :migration', $this->migrationsTable));
        $stmt->execute([
            'migration' => $migration,
        ]);
    }
}
