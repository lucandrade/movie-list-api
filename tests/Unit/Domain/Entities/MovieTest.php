<?php
declare(strict_types=1);

namespace MovieList\Tests\Unit\Domain\Entities;

use MovieList\Domain\Entities\Movie;
use MovieList\Tests\Unit\TestCase;

final class MovieTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_a_movie()
    {
        $id = 'some-id';
        $title = 'some-title';
        $year = 'some-year';
        $image = 'http://image.com/image.png';
        $movie = new Movie($id, $title, $year, $image);
        $this->assertThat($id, $this->identicalTo($movie->getId()));
        $this->assertThat([
            'id' => $id,
            'title' => $title,
            'year' => $year,
            'image' => $image,
        ], $this->identicalTo($movie->toArray()));
    }

    /**
     * @test
     */
    public function it_can_create_a_movie_without_image()
    {
        $id = 'some-id';
        $title = 'some-title';
        $year = 'some-year';
        $image = 'invalid-image';
        $movie = new Movie($id, $title, $year, $image);
        $this->assertThat([
            'id' => $id,
            'title' => $title,
            'year' => $year,
            'image' => null,
        ], $this->identicalTo($movie->toArray()));
    }
}
