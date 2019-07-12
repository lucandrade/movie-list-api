<?php
declare(strict_types=1);

namespace MovieList\Domain\Entities;

use MovieList\Domain\Contracts\Arrayable;

final class Movie implements Arrayable
{
    /** @var string */
    private $id;

    /** @var string */
    private $title;

    /** @var string */
    private $year;

    /** @var ?string */
    private $image;

    public function __construct(string $id, string $title, string $year, string $image)
    {
        $this->id = $id;
        $this->title = $title;
        $this->year = $year;
        $this->image = filter_var($image, FILTER_VALIDATE_URL) ? $image : null;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'year' => $this->year,
            'image' => $this->image,
        ];
    }
}
