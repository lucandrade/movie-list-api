<?php
declare(strict_types=1);

namespace MovieList\Infra\Factories;

use MovieList\Domain\Entities\Movie;

final class MovieFactory
{
    /** @var string */
    private $imageBaseUrl;

    public function __construct(string $imageBaseUrl)
    {
        $this->imageBaseUrl = $imageBaseUrl;
    }

    private function buildImageUrlFromApiResult(array $apiResult): ?string
    {
        $imagePath = array_get($apiResult, 'poster_path');

        if (!empty($imagePath)) {
            return "{$this->imageBaseUrl}{$imagePath}";
        }

        return null;
    }

    public function fromApiResult(array $apiResult): Movie
    {
        return new Movie(
            strval(array_get($apiResult, 'id')),
            array_get($apiResult, 'title'),
            array_get($apiResult, 'release_date'),
            $this->buildImageUrlFromApiResult($apiResult)
        );
    }
}
