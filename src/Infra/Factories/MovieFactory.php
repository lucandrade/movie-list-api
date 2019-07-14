<?php
declare(strict_types=1);

namespace MovieList\Infra\Factories;

use MovieList\Domain\Entities\Movie;
use MovieList\Domain\Entities\MovieDetails;
use MovieList\Domain\ValueObjects\Review;
use MovieList\Domain\ValueObjects\Trailer;

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

    public function build(array $apiResult): Movie
    {
        return new Movie(
            strval(array_get($apiResult, 'id')),
            array_get($apiResult, 'title'),
            array_get($apiResult, 'release_date'),
            $this->buildImageUrlFromApiResult($apiResult)
        );
    }

    private function buildGenreListFromApiResult(array $apiResult): array
    {
        $list = array_get($apiResult, 'genres');

        if (!is_array($list)) {
            return [];
        }

        return array_map(function (array $item) {
            return $item['name'];
        }, $list);
    }

    private function buildTrailerListFromApiResult(array $apiResult): array
    {
        $list = array_get($apiResult, 'trailers');

        if (!is_array($list)) {
            return [];
        }

        return array_map(function (array $item) {
            return new Trailer($item['key'], $item['name']);
        }, $list);
    }

    private function buildReviewListFromApiResult(array $apiResult): array
    {
        $list = array_get($apiResult, 'reviews');

        if (!is_array($list)) {
            return [];
        }

        return array_map(function (array $item) {
            return new Review($item['author'], $item['content'], $item['url']);
        }, $list);
    }

    public function buildDetails(array $apiResult): MovieDetails
    {
        return new MovieDetails(
            strval(array_get($apiResult, 'id')),
            array_get($apiResult, 'title'),
            array_get($apiResult, 'release_date'),
            $this->buildImageUrlFromApiResult($apiResult),
            array_get($apiResult, 'homepage'),
            array_get($apiResult, 'overview'),
            $this->buildGenreListFromApiResult($apiResult),
            $this->buildTrailerListFromApiResult($apiResult),
            $this->buildReviewListFromApiResult($apiResult)
        );
    }
}
