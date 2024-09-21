<?php

return [
  'POST' => [
    '/books' => 'App\Presentation\Controllers\BookController@createBook',
  ],
  'GET' => [
    '/books' => 'App\Presentation\Controllers\BookController@listBooks',
    '/books/:id' => 'App\Presentation\Controllers\BookController@listOneBook',
    '/report' => 'App\Presentation\Controllers\ReportController@generateReport',
  ],
  'PUT' => [
    '/books/:id' => 'App\Presentation\Controllers\BookController@updateBook',
  ],
  'DELETE' => [
    '/books/:id' => 'App\Presentation\Controllers\BookController@deleteBook',
  ],
];