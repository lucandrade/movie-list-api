<?php
declare(strict_types=1);

namespace MovieList\Infra\Http\Controllers;

use Illuminate\Http\Request;
use MovieList\App\UseCases\SearchUseCase;
use MovieList\Domain\Exceptions\InvalidArgumentException;
use MovieList\Domain\Exceptions\RuntimeException;
use MovieList\Domain\ValueObjects\Options;
use MovieList\Domain\ValueObjects\Query;
use MovieList\Infra\Http\ApiResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

final class SearchController
{
    /** @var SearchUseCase */
    private $useCase;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(SearchUseCase $useCase, LoggerInterface $logger)
    {
        $this->useCase = $useCase;
        $this->logger = $logger;
    }

    public function handle(Request $request): ResponseInterface
    {
        if (!$request->filled('q')) {
            return ApiResponse::error('Query is required');
        }

        try {
            $pageNumber = $request->input('page', 1);
            $query = new Query($request->input('q'));

            $page = $this->useCase->run(new Options(intval($pageNumber), $query));
            return ApiResponse::success($page->toArray());
        } catch (RuntimeException $e) {
            $this->logger->error("Error searching movies", [
                'query' => $request->input('q'),
                'e' => $e,
            ]);
        } catch (InvalidArgumentException $e) {
            $this->logger->error("Error searching movies", [
                'query' => $request->input('q'),
                'e' => $e,
            ]);
        }

        return ApiResponse::error("Error searching movies");
    }
}
