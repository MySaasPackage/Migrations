<?php

declare(strict_types=1);

use MySaasPackage\Migrations\Context;

$up = function (PDO $pdo, Context $context = null): void {
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)
SQL;

    $pdo->exec($sql);
};

$down = function (PDO $pdo, Context $context = null): void {
    $pdo->exec('DROP TABLE IF EXISTS users');
};

return ['up' => $up, 'down' => $down];
