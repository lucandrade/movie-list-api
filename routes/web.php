<?php

Route::get('/', 'ListPopularController@handle');
Route::get('/search', 'SearchController@handle');
Route::get('/get/{id}', 'GetMovieController@handle');
