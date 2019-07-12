<?php
declare(strict_types=1);

namespace MovieList\Domain\ValueObjects;

use MovieList\Domain\Contracts\Arrayable;
use MovieList\Domain\Exceptions\InvalidArgumentException;

final class Page implements Arrayable
{
    /** @var Options */
    private $options;

    /** @var int */
    private $totalItems;

    /** @var array */
    private $contents;

    public static function empty(): self
    {
        return new self(Options::default(), 0, []);
    }

    private function validate(): void
    {
        if ($this->totalItems < count($this->contents)) {
            throw new InvalidArgumentException("Page cannot have more items than the total");
        }

        array_walk($this->contents, function ($item) {
            if (!is_object($item)) {
                throw new InvalidArgumentException("Page content must be Arrayable");
            }

            if (!$item instanceof Arrayable) {
                throw new InvalidArgumentException("Page content must be Arrayable");
            }
        });
    }

    public function __construct(Options $options, int $totalItems, array $contents)
    {
        $this->options = $options;
        $this->totalItems = $totalItems;
        $this->contents = $contents;
        $this->validate();
    }

    public function getOptions(): Options
    {
        return $this->options;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getContents(): array
    {
        return $this->contents;
    }

    public function isEmpty(): bool
    {
        return $this->totalItems < 1;
    }

    public function toArray(): array
    {
        return [
            'page' => $this->getOptions()->getPageNumber(),
            'q' => strval($this->getOptions()->getQuery()),
            'total' => $this->getTotalItems(),
            'items' => array_map(function (Arrayable $item) {
                return $item->toArray();
            }, $this->getContents()),
        ];
    }
}
