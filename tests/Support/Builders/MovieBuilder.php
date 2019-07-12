<?php
declare(strict_types=1);

namespace MovieList\Tests\Support\Builders;

use MovieList\Domain\Entities\Movie;

final class MovieBuilder
{
    /** @var string */
    private const DEFAULT_ID = 'some-id';

    /** @var string */
    private const DEFAULT_TITLE = 'some-title';

    /** @var string */
    private const DEFAULT_YEAR = '2000';

    /** @var string */
    private const DEFAULT_IMAGE = 'https://server.com/image.png';

    /** @var string */
    private $id;

    /** @var string */
    private $title;

    /** @var string */
    private $year;

    /** @var ?string */
    private $image;

    public function withId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function withTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function withYear(string $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function withImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public static function fromDefaults(): self
    {
        return (new self)
            ->withId(self::DEFAULT_ID)
            ->withTitle(self::DEFAULT_TITLE)
            ->withYear(self::DEFAULT_YEAR)
            ->withImage(self::DEFAULT_IMAGE);
    }

    public function build(): Movie
    {
        return new Movie($this->id, $this->title, $this->year, $this->image);
    }
}
