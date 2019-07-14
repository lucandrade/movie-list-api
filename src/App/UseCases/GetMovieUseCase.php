<?php
declare(strict_types=1);

namespace MovieList\App\UseCases;

use MovieList\Domain\Entities\MovieDetails;
use MovieList\Domain\Repositories\MovieRepository;

final class GetMovieUseCase
{
    /** @var MovieRepository */
    private $repository;

    public function __construct(MovieRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run(string $movieId): ?MovieDetails
    {
        return $this->repository->get($movieId);
    }
}
