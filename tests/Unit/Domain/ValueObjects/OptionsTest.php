<?php
declare(strict_types=1);

namespace MovieList\Tests\Unit\Domain\ValueObjects;

use MovieList\Domain\ValueObjects\Options;
use MovieList\Domain\ValueObjects\Query;
use MovieList\Tests\Unit\TestCase;

final class OptionsTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_options()
    {
        $pageNumber = 2;
        $query = new Query("text");
        $options = new Options($pageNumber, $query);
        $this->assertThat($pageNumber, $this->identicalTo($options->getPageNumber()));
        $this->assertThat($query, $this->identicalTo($options->getQuery()));
    }

    /**
     * @test
     */
    public function it_can_create_options_from_default()
    {
        $options = Options::default();
        $this->assertTrue($options->getQuery()->isEmpty());
    }
}
