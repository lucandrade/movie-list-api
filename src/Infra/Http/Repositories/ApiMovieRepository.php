<?php
declare(strict_types=1);

namespace MovieList\Infra\Http\Repositories;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
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

    private function buildQuery(array $query): array
    {
        return [
            'query' => array_merge(
                $this->client->getConfig('query'),
                $query
            ),
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
                return $this->factory->fromApiResult($item);
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
                return $this->factory->fromApiResult($item);
            }, $items));
        } catch (RequestException $e) {
            throw new RuntimeException($e->getMessage(), intval($e->getCode()), $e);
        }
    }
}
