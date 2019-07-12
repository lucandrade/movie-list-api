<?php
declare(strict_types=1);

namespace MovieList\Domain\Repositories;

use MovieList\Domain\ValueObjects\Options;
use MovieList\Domain\ValueObjects\Page;

interface MovieRepository
{
    public function search(Options $options): Page;
    public function listPopular(Options $options): Page;
}
