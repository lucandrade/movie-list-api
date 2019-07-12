<?php
declare(strict_types=1);

namespace MovieList\Infra\Http\Controllers;

use Illuminate\Http\Request;
use MovieList\App\UseCases\ListPopularUseCase;
use MovieList\Domain\Exceptions\InvalidArgumentException;
use MovieList\Domain\Exceptions\RuntimeException;
use MovieList\Domain\ValueObjects\Options;
use MovieList\Domain\ValueObjects\Query;
use MovieList\Infra\Http\ApiResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

final class ListPopularController
{
    /** @var ListPopularUseCase */
    private $useCase;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(ListPopularUseCase $useCase, LoggerInterface $logger)
    {
        $this->useCase = $useCase;
        $this->logger = $logger;
    }

    public function handle(Request $request): ResponseInterface
    {
        try {
            $pageNumber = $request->input('page', 1);
            $query = new Query("");

            $page = $this->useCase->run(new Options(intval($pageNumber), $query));
            return ApiResponse::success($page->toArray());
        } catch (RuntimeException $e) {
            $this->logger->error("Error listing movies", [
                'query' => $request->input('q'),
                'e' => $e,
            ]);
        } catch (InvalidArgumentException $e) {
            $this->logger->error("Error listing movies", [
                'query' => $request->input('q'),
                'e' => $e,
            ]);
        }

        return ApiResponse::error("Error listing movies");
    }
}
