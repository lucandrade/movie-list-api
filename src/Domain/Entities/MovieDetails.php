<?php
declare(strict_types=1);

namespace MovieList\Domain\Entities;

use MovieList\Domain\Contracts\Arrayable;

final class MovieDetails implements Arrayable
{
    /** @var string */
    private $id;

    /** @var string */
    private $title;

    /** @var string */
    private $year;

    /** @var ?string */
    private $image;

    /** @var string */
    private $link;

    /** @var string */
    private $description;

    /** @var array */
    private $genreList;

    /** @var array */
    private $trailerList;

    /** @var array */
    private $reviewList;

    public function __construct(
        string $id,
        string $title,
        string $year,
        ?string $image,
        string $link,
        string $description,
        array $genreList,
        array $trailerList,
        array $reviewList
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->year = $year;
        $this->image = $image;
        $this->link = $link;
        $this->description = $description;
        $this->genreList = $genreList;
        $this->trailerList = $trailerList;
        $this->reviewList = $reviewList;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'year' => $this->year,
            'image' => $this->image,
            'link' => $this->link,
            'description' => $this->description,
            'genres' => $this->genreList,
            'trailers' => array_map(function (Arrayable $item) {
                return $item->toArray();
            }, $this->trailerList),
            'reviews' => array_map(function (Arrayable $item) {
                return $item->toArray();
            }, $this->reviewList),
        ];
    }
}
