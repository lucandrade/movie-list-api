<?php
declare(strict_types=1);

namespace MovieList\App\UseCases;

use MovieList\Domain\Repositories\MovieRepository;
use MovieList\Domain\ValueObjects\Options;
use MovieList\Domain\ValueObjects\Page;

final class SearchUseCase
{
    /** @var MovieRepository */
    private $repository;

    public function __construct(MovieRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run(Options $options): Page
    {
        return $this->repository->search($options);
    }
}
