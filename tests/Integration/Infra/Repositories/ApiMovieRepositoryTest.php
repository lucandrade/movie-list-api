<?php
declare(strict_types=1);

namespace MovieList\Tests\Integration\Infra\Repositories;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use MovieList\Domain\ValueObjects\Options;
use MovieList\Domain\ValueObjects\Query;
use MovieList\Infra\Factories\MovieFactory;
use MovieList\Infra\Repositories\ApiMovieRepository;
use MovieList\Tests\Integration\TestCase;
use Teapot\StatusCode;

final class ApiMovieRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_list_popular()
    {
        $mock = new MockHandler([
            new Response(StatusCode::OK, [], json_encode([
                'total_results' => 1,
                'results' => [
                    [
                        'id' => '123',
                        'title' => 'Movie Title',
                        'release_date' => date('Y-m-d'),
                    ],
                ],
            ])),
        ]);
        $client = new Client([
            'handler' => $mock,
        ]);
        $repository = new ApiMovieRepository($client, new MovieFactory(""));
        $options = Options::default();
        $result = $repository->search($options);
        $this->assertThat(1, $this->identicalTo($result->getOptions()->getPageNumber()));
        $this->assertThat(1, $this->identicalTo($result->getTotalItems()));
        $this->assertThat(1, $this->identicalTo(count($result->getContents())));
    }

    /**
     * @test
     */
    public function it_can_search()
    {
        $mock = new MockHandler([
            new Response(StatusCode::OK, [], json_encode([
                'total_results' => 1,
                'results' => [
                    [
                        'id' => '123',
                        'title' => 'Movie Title',
                        'release_date' => date('Y-m-d'),
                    ],
                ],
            ])),
        ]);
        $client = new Client([
            'handler' => $mock,
        ]);
        $repository = new ApiMovieRepository($client, new MovieFactory(""));
        $options = new Options(1, new Query("text"));
        $result = $repository->search($options);
        $this->assertThat(1, $this->identicalTo($result->getOptions()->getPageNumber()));
        $this->assertThat(1, $this->identicalTo($result->getTotalItems()));
        $this->assertThat(1, $this->identicalTo(count($result->getContents())));
        $this->assertThat("text", $this->identicalTo(strval($result->getOptions()->getQuery())));
    }
}
