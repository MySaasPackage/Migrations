<?php

declare(strict_types=1);

use MySaasPackage\Migrations\Context;

$up = function (PDO $pdo, Context $context = null): void {
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS subscriptions (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL
);

ALTER TABLE subscriptions ADD CONSTRAINT fk_subscriptions_users FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
SQL;

    $pdo->exec($sql);
};

$down = function (PDO $pdo, Context $context = null): void {
    $pdo->exec('ALTER TABLE subscriptions DROP CONSTRAINT fk_subscriptions_users;');
    $pdo->exec('DROP TABLE subscriptions;');
};

return ['up' => $up, 'down' => $down];
