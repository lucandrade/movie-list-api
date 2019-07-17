<?php
declare(strict_types=1);

namespace MovieList\Infra\Repositories;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use MovieList\Domain\Entities\MovieDetails;
use MovieList\Domain\Exceptions\RuntimeException;
use MovieList\Domain\Repositories\MovieRepository;
use MovieList\Domain\ValueObjects\Options;
use MovieList\Domain\ValueObjects\Page;
use MovieList\Infra\Factories\MovieFactory;
use Teapot\StatusCode;

final class ApiMovieRepository implements MovieRepository
{
    /** @var Client */
    private $client;

    /** @var MovieFactory */
    private $factory;

    public function __construct(Client $client, MovieFactory $factory)
    {
        $this->client = $client;
        $this->factory = $factory;
    }

    private function buildQuery(array $query = []): array
    {
        if (is_array($this->client->getConfig('query'))) {
            return [
                'query' => array_merge(
                    $this->client->getConfig('query'),
                    $query
                ),
            ];
        }

        return [
            'query' => $query,
        ];
    }

    public function search(Options $options): Page
    {
        try {
            $response = $this->client->get("/3/search/movie", $this->buildQuery([
                'query' => strval($options->getQuery()),
                'page' => $options->getPageNumber(),
            ]));

            if ($response->getStatusCode() !== StatusCode::OK) {
                throw new RuntimeException("Error calling api");
            }

            $body = json_decode($response->getBody()->getContents(), true);
            $totalItems = array_get($body, 'total_results');
            $items = array_get($body, 'results');

            if (!is_numeric($totalItems) || !is_array($items)) {
                throw new RuntimeException("Invalid api response");
            }

            return new Page($options, intval($totalItems), array_map(function (array $item) {
                return $this->factory->build($item);
            }, $items));
        } catch (RequestException $e) {
            throw new RuntimeException($e->getMessage(), intval($e->getCode()), $e);
        }
    }

    public function listPopular(Options $options): Page
    {
        try {
            $response = $this->client->get("/3/movie/popular", $this->buildQuery([
                'page' => $options->getPageNumber(),
            ]));

            if ($response->getStatusCode() !== StatusCode::OK) {
                throw new RuntimeException("Error calling api");
            }

            $body = json_decode($response->getBody()->getContents(), true);
            $totalItems = array_get($body, 'total_results');
            $items = array_get($body, 'results');

            if (!is_numeric($totalItems) || !is_array($items)) {
                throw new RuntimeException("Invalid api response");
            }

            return new Page($options, intval($totalItems), array_map(function (array $item) {
                return $this->factory->build($item);
            }, $items));
        } catch (RequestException $e) {
            throw new RuntimeException($e->getMessage(), intval($e->getCode()), $e);
        }
    }

    private function getTrailerList(string $movieId): array
    {
        try {
            $response = $this->client->get("/3/movie/{$movieId}/videos", $this->buildQuery());

            if ($response->getStatusCode() !== StatusCode::OK) {
                return [];
            }

            $body = json_decode($response->getBody()->getContents(), true);
            $items = array_get($body, 'results');

            if (!is_array($items)) {
                return [];
            }

            return $items;
        } catch (RequestException $e) {
            return [];
        }
    }

    private function getReviewList(string $movieId): array
    {
        try {
            $response = $this->client->get("/3/movie/{$movieId}/reviews", $this->buildQuery());

            if ($response->getStatusCode() !== StatusCode::OK) {
                return [];
            }

            $body = json_decode($response->getBody()->getContents(), true);
            $items = array_get($body, 'results');

            if (!is_array($items)) {
                return [];
            }

            return $items;
        } catch (RequestException $e) {
            return [];
        }
    }

    public function get(string $movieId): ?MovieDetails
    {
        try {
            $response = $this->client->get("/3/movie/{$movieId}", $this->buildQuery());

            if ($response->getStatusCode() !== StatusCode::OK) {
                return null;
            }

            $body = json_decode($response->getBody()->getContents(), true);
            $movieDetailsData = array_merge(
                $body,
                [
                    'trailers' => $this->getTrailerList($movieId),
                ],
                [
                    'reviews' => $this->getReviewList($movieId),
                ]
            );

//            dd($movieDetailsData);

            return $this->factory->buildDetails($movieDetailsData);
        } catch (RequestException $e) {
            throw new RuntimeException($e->getMessage(), intval($e->getCode()), $e);
        }

        return null;
    }
}
