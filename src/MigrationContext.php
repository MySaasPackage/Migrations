<?php

declare(strict_types=1);

namespace MySaasPackage\Migrations;

class MigrationContext
{
    protected $data = [];

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }

    public function get($key)
    {
        return $this->data[$key] ?? null;
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function merge(MigrationContext|null $context): MigrationContext
    {
        if (is_null($context)) {
            return $this;
        }

        return new static(array_merge($this->data, $context->data));
    }
}
