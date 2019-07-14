<?php
declare(strict_types=1);

namespace MovieList\Domain\ValueObjects;

use MovieList\Domain\Contracts\Arrayable;

final class Review implements Arrayable
{
    /** @var string */
    private $author;

    /** @var string */
    private $text;

    /** @var string */
    private $link;

    public function __construct(string $author, string $text, string $link)
    {
        $this->author = $author;
        $this->text = $text;
        $this->link = $link;
    }

    public function toArray(): array
    {
        return [
            'author' => $this->author,
            'text' => $this->text,
            'link' => $this->link,
        ];
    }
}
