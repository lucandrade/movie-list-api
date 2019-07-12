<?php
declare(strict_types=1);

namespace MovieList\Infra\Http;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Teapot\StatusCode;

final class ApiResponse
{
    /** @var string */
    private const CONTENT_TYPE = 'application/json';

    private static function buildResponse(int $statusCode): ResponseInterface
    {
        return (new Response)
            ->withStatus($statusCode)
            ->withHeader('Content-type', self::CONTENT_TYPE);
    }

    private static function buildData(bool $status, $payload = null, $message = null): string
    {
        return json_encode([
            'status' => $status,
            'payload' => $payload,
            'message' => $message,
            'rendered' => Carbon::now()->timestamp,
        ]);
    }

    public static function success($payload, $statusCode = StatusCode::OK): ResponseInterface
    {
        $response = self::buildResponse($statusCode);
        $response->getBody()->write(
            self::buildData(true, $payload)
        );

        return $response;
    }

    public static function error($message, $statusCode = StatusCode::OK): ResponseInterface
    {
        $response = self::buildResponse($statusCode);
        $response->getBody()->write(
            self::buildData(false, null, $message)
        );

        return $response;
    }
}
