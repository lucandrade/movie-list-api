<?php
declare(strict_types=1);

namespace MovieList\Tests\Unit\Domain\ValueObjects;

use MovieList\Domain\Exceptions\InvalidArgumentException;
use MovieList\Domain\ValueObjects\Options;
use MovieList\Domain\ValueObjects\Page;
use MovieList\Tests\Support\Builders\MovieBuilder;
use MovieList\Tests\Unit\TestCase;

final class PageTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_a_page()
    {
        $options = Options::default();
        $totalItems = 1;
        $contents = [MovieBuilder::fromDefaults()->build()];
        $page = new Page($options, $totalItems, $contents);
        $this->assertThat($options, $this->identicalTo($page->getOptions()));
        $this->assertThat($totalItems, $this->identicalTo($page->getTotalItems()));
        $this->assertThat($contents, $this->identicalTo($page->getContents()));
        $pageData = $page->toArray();
        $this->assertArrayHasKey('page', $pageData);
        $this->assertArrayHasKey('q', $pageData);
        $this->assertArrayHasKey('total', $pageData);
        $this->assertArrayHasKey('items', $pageData);
    }

    /**
     * @test
     */
    public function it_can_create_an_empty_page()
    {
        $page = Page::empty();
        $this->assertTrue($page->isEmpty());
    }

    /**
     * @test
     */
    public function it_cannot_create_page_with_more_items_than_the_total()
    {
        $this->expectException(InvalidArgumentException::class);
        new Page(Options::default(), 0, [MovieBuilder::fromDefaults()->build()]);
    }

    /**
     * @test
     */
    public function it_cannot_create_page_with_invalid_content()
    {
        $this->expectException(InvalidArgumentException::class);
        new Page(Options::default(), 1, ['']);
    }
}
