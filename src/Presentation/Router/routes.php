<?php

return [
  'POST' => [
    '/books' => 'App\Presentation\Controller\BookController@createBook',
  ],
  'GET' => [
    '/books' => 'App\Presentation\Controller\BookController@listBooks',
    '/books/:id' => 'App\Presentation\Controller\BookController@listOneBook',
    '/report' => 'App\Presentation\Controller\ReportController@generateReport',
  ],
  'PUT' => [
    '/books/:id' => 'App\Presentation\Controller\BookController@updateBook',
  ],
  'DELETE' => [
    '/books/:id' => 'App\Presentation\Controller\BookController@deleteBook',
  ],
];