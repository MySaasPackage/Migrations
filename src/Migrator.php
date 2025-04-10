<?php

declare(strict_types=1);

namespace MySaasPackage\Migrations;

use PDO;

class Migrator
{
    protected MigrationContext $defaultContext;

    protected readonly EnsureMigrator $ensureMigrator;

    protected readonly UpwardMigrator $upwardMigrator;

    protected readonly DownwardMigrator $downwardMigrator;

    public function __construct(
        protected readonly PDO $pdo,
        protected readonly string $migrationsDir,
        protected readonly string $defaultMigrationsTable = 'public.migrations',
    ) {
        $this->defaultContext = new MigrationContext();
        $this->upwardMigrator = new UpwardMigrator($pdo, $this->defaultMigrationsTable, $this->migrationsDir);
        $this->downwardMigrator = new DownwardMigrator($pdo, $this->defaultMigrationsTable, $this->migrationsDir);
        $this->ensureMigrator = new EnsureMigrator($pdo, $this->defaultMigrationsTable);
    }

    public function withDefaultContext(MigrationContext $context): Migrator
    {
        $this->defaultContext = $this->defaultContext->merge($context);

        return $this;
    }

    public function up(?MigrationContext $customContext = null): Migrator
    {
        $this->ensureMigrator->ensureMigrationsTableExists();
        $this->upwardMigrator->migrate($this->defaultContext->merge($customContext));

        return $this;
    }

    public function down(?MigrationContext $customContext = null): Migrator
    {
        $this->ensureMigrator->ensureMigrationsTableExists();
        $this->downwardMigrator->migrate($this->defaultContext->merge($customContext));

        return $this;
    }
}
