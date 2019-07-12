<?php
declare(strict_types=1);

namespace MovieList\Domain\ValueObjects;

final class Query
{
    /** @var ?string */
    private $value;

    public static function empty(): self
    {
        return new self(null);
    }

    public function __construct(?string $value)
    {
        $this->value = $value;
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function __toString(): string
    {
        return $this->value ?: "";
    }
}
