<?php

namespace App\Services;

class AlumniImportResult
{
    /**
     * @param  list<string>  $errors  Human-readable, row-scoped error messages.
     */
    public function __construct(
        public int $created = 0,
        public int $updated = 0,
        public int $skipped = 0,
        public array $errors = [],
    ) {}

    public function processed(): int
    {
        return $this->created + $this->updated;
    }

    public function hasErrors(): bool
    {
        return $this->errors !== [];
    }

    public function summary(): string
    {
        $parts = [
            "{$this->created} ditambahkan",
            "{$this->updated} diperbarui",
        ];

        if ($this->skipped > 0) {
            $parts[] = "{$this->skipped} dilewati";
        }

        return implode(', ', $parts).'.';
    }
}
