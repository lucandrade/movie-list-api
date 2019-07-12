<?php
declare(strict_types=1);

namespace MovieList\Tests\Unit\Domain\ValueObjects;

use MovieList\Domain\ValueObjects\Query;
use MovieList\Tests\Unit\TestCase;

final class QueryTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_a_query()
    {
        $query = new Query("text");
        $this->assertThat("text", $this->identicalTo(strval($query)));
        $this->assertFalse($query->isEmpty());
    }

    /**
     * @test
     */
    public function it_can_create_an_empty_query_from_named_constructor()
    {
        $query = Query::empty();
        $this->assertThat("", $this->identicalTo(strval($query)));
        $this->assertTrue($query->isEmpty());
    }

    /**
     * @test
     */
    public function it_can_create_an_empty_query_with_empty_string()
    {
        $query = new Query("");
        $this->assertThat("", $this->identicalTo(strval($query)));
        $this->assertTrue($query->isEmpty());
    }
}
