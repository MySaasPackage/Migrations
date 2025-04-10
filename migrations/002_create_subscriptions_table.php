<?php

declare(strict_types=1);

use MySaasPackage\Migrations\MigrationContext;

$up = function (PDO $pdo, ?MigrationContext $context = null): void {
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS public.subscriptions (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL
);

ALTER TABLE public.subscriptions ADD CONSTRAINT fk_subscriptions_users FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;
SQL;

    $pdo->exec($sql);
};

$down = function (PDO $pdo, ?MigrationContext $context = null): void {
    $pdo->exec('ALTER TABLE public.subscriptions DROP CONSTRAINT fk_subscriptions_users;');
    $pdo->exec('DROP TABLE public.subscriptions;');
};

return ['up' => $up, 'down' => $down];
