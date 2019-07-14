<?php
declare(strict_types=1);

namespace MovieList\Infra\Http\Controllers;

use MovieList\App\UseCases\GetMovieUseCase;
use MovieList\Domain\Exceptions\InvalidArgumentException;
use MovieList\Domain\Exceptions\RuntimeException;
use MovieList\Infra\Http\ApiResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

final class GetMovieController
{
    /** @var GetMovieUseCase */
    private $useCase;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(GetMovieUseCase $useCase, LoggerInterface $logger)
    {
        $this->useCase = $useCase;
        $this->logger = $logger;
    }

    public function handle(string $movieId): ResponseInterface
    {
        try {
            $movie = $this->useCase->run($movieId);

            if (is_null($movie)) {
                return ApiResponse::error("Movie not found");
            }

            return ApiResponse::success($movie->toArray());
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
