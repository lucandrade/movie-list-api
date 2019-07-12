<?php
declare(strict_types=1);

namespace MovieList\Infra\Http\Controllers;

use Illuminate\Http\Request;
use MovieList\Infra\Http\ApiResponse;
use Psr\Http\Message\ResponseInterface;

final class SearchController
{
    public function handle(Request $request): ResponseInterface
    {
        return ApiResponse::success($request->all());
    }
}
