<?php
declare(strict_types=1);

namespace MovieList\Infra\Http\Controllers;

use MovieList\App\UseCases\ListSimilarUseCase;
use MovieList\Domain\Exceptions\InvalidArgumentException;
use MovieList\Domain\Exceptions\RuntimeException;
use MovieList\Infra\Http\ApiResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

final class ListSimilarController
{
    /** @var ListSimilarUseCase */
    private $useCase;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(ListSimilarUseCase $useCase, LoggerInterface $logger)
    {
        $this->useCase = $useCase;
        $this->logger = $logger;
    }

    public function handle(string $movieId): ResponseInterface
    {
        try {
            $movie = $this->useCase->run($movieId);
            return ApiResponse::success($movie->toArray());
        } catch (RuntimeException $e) {
            $this->logger->error("Error getting similar movies", [
                'movieId' => $movieId,
                'e' => $e,
            ]);
        } catch (InvalidArgumentException $e) {
            $this->logger->error("Error getting similar movies", [
                'movieId' => $movieId,
                'e' => $e,
            ]);
        }

        return ApiResponse::error('Similar movies not found');
    }
}
