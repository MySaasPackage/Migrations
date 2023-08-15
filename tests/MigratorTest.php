<?php

declare(strict_types=1);

namespace MySaasPackage\Migrations;

use PDO;
use PHPUnit\Framework\TestCase;

class MigratorTest extends TestCase
{
    protected readonly PDO $pdo;
    protected readonly Migrator $migrator;

    public function setUp(): void
    {
        $databaseHost = 'localhost';
        $databasePort = '5432';
        $databaseName = 'eclesi';
        $databaseUser = 'eclesi';
        $databasePass = 's3cr3t';

        $this->pdo = new PDO("pgsql:host={$databaseHost};port={$databasePort};dbname={$databaseName}", $databaseUser, $databasePass);
        $migrationsDir = dirname(__DIR__) . '/migrations';
        $this->migrator = new Migrator($this->pdo, $migrationsDir);
    }

    public function testMigratorUp(): void
    {
        $this->migrator->up();

        $stmt = $this->pdo->query('SELECT * FROM migrations');
        $stmt->execute();
        $this->assertEquals(2, $stmt->rowCount());
        $result = array_map(fn ($row) => $row['migration'], $stmt->fetchAll());
        $this->assertEquals(['001_create_users_table', '002_create_subscriptions_table'], $result);

        $this->migrator->down();
    }

    public function testMigratorDown(): void
    {
        $this->migrator->up();
        $this->migrator->down();

        $stmt = $this->pdo->query('SELECT COUNT(*) FROM pg_tables WHERE schemaname = \'public\';');
        $stmt->execute();
        $this->assertEquals(1, $stmt->fetchColumn());

        $stmt = $this->pdo->query('SELECT COUNT(*) migrations;');
        $stmt->execute();
        $this->assertEquals(1, $stmt->fetchColumn());
    }
}
