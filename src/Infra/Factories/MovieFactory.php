<?php
declare(strict_types=1);

namespace MovieList\Infra\Factories;

use MovieList\Domain\Entities\Movie;

final class MovieFactory
{
    public function fromApiResult(array $apiResult): Movie
    {
        return new Movie(
            strval(array_get($apiResult, 'id')),
            array_get($apiResult, 'title'),
            array_get($apiResult, 'release_date'),
            array_get($apiResult, 'poster_path')
        );
    }
}
