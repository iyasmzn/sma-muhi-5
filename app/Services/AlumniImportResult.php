<?php

namespace App\Services;

class AlumniImportResult
{
    /**
     * @param  list<string>  $errors  Human-readable, row-scoped failure messages (one per failed row).
     */
    public function __construct(
        public int $created = 0,
        public int $updated = 0,
        public array $errors = [],
    ) {}

    public function processed(): int
    {
        return $this->created + $this->updated;
    }

    public function failed(): int
    {
        return count($this->errors);
    }

    public function total(): int
    {
        return $this->processed() + $this->failed();
    }

    public function hasErrors(): bool
    {
        return $this->errors !== [];
    }
}
