<?php
declare(strict_types=1);

namespace MovieList\Domain\ValueObjects;

final class Options
{
    /** @var int */
    private const DEFAULT_PAGE_NUMBER = 1;

    /** @var int */
    private $pageNumber;

    /** @var Query */
    private $query;

    public static function default(): self
    {
        return new self(self::DEFAULT_PAGE_NUMBER, Query::empty());
    }

    public function __construct(int $pageNumber, Query $query)
    {
        $this->pageNumber = $pageNumber;
        $this->query = $query;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    public function getQuery(): Query
    {
        return $this->query;
    }
}
