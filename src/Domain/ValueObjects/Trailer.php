<?php
declare(strict_types=1);

namespace MovieList\Domain\ValueObjects;

use MovieList\Domain\Contracts\Arrayable;

final class Trailer implements Arrayable
{
    /** @var string */
    private $youtubeKey;

    /** @var string */
    private $title;

    public function __construct(string $youtubeKey, string $title)
    {
        $this->youtubeKey = $youtubeKey;
        $this->title = $title;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->youtubeKey,
            'title' => $this->title,
        ];
    }
}
