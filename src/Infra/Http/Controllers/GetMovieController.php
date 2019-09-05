<?php
declare(strict_types=1);

namespace MovieList\Infra\Http\Controllers;

use MovieList\App\UseCases\GetMovieUseCase;
use MovieList\App\UseCases\ListSimilarUseCase;
use MovieList\Domain\Exceptions\InvalidArgumentException;
use MovieList\Domain\Exceptions\RuntimeException;
use MovieList\Infra\Http\ApiResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

final class GetMovieController
{
    /** @var GetMovieUseCase */
    private $getMovieUseCase;

    /** @var ListSimilarUseCase */
    private $listSimilarUseCase;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(GetMovieUseCase $useCase, ListSimilarUseCase $listSimilarUseCase, LoggerInterface $logger)
    {
        $this->getMovieUseCase = $useCase;
        $this->listSimilarUseCase = $listSimilarUseCase;
        $this->logger = $logger;
    }

    public function handle(string $movieId): ResponseInterface
    {
        try {
            $movie = $this->getMovieUseCase->run($movieId);

            if (is_null($movie)) {
                return ApiResponse::error("Movie not found");
            }

            $movieData = $movie->toArray();
            $similarMovies = $this->listSimilarUseCase->run($movieId);

            $movieData['similar'] = $similarMovies->toArray()['items'];

            return ApiResponse::success($movieData);
        } catch (RuntimeException $e) {
            $this->logger->error("Error getting movie", [
                'movieId' => $movieId,
                'e' => $e,
            ]);
        } catch (InvalidArgumentException $e) {
            $this->logger->error("Error getting movie", [
                'movieId' => $movieId,
                'e' => $e,
            ]);
        }

        return ApiResponse::error('Movie not found');
    }
}
