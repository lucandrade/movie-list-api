<?php

namespace MovieList\Infra\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use MovieList\Domain\Repositories\MovieRepository;
use MovieList\Infra\Factories\MovieFactory;
use MovieList\Infra\Http\Repositories\ApiMovieRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MovieRepository::class, function () {
            return new ApiMovieRepository(
                new Client([
                    'base_uri' => env('API_URL'),
                    'query' => [
                        'api_key' => env('API_KEY'),
                    ],
                ]),
                new MovieFactory
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
