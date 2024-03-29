<?php
declare(strict_types=1);

namespace MovieList\Domain\Contracts;

interface Arrayable
{
    public function toArray(): array;
}
