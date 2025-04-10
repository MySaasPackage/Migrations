<?php

declare(strict_types=1);

use MySaasPackage\Migrations\MigrationContext;

$up = function (PDO $pdo): void {
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS public.users (
    id INTEGER PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
SQL;

    $pdo->exec($sql);
};

$down = function (PDO $pdo): void {
    $pdo->exec('DROP TABLE IF EXISTS public.users');
};

return ['up' => $up, 'down' => $down];
