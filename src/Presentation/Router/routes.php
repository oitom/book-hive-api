<?php

return [
  'POST' => [
    '/books' => 'App\Presentation\Controller\BookController@createBook',
  ],
  'GET' => [
    '/books' => 'App\Presentation\Controller\BookController@listBooks',
    '/books/:id' => 'App\Presentation\Controller\BookController@listOneBook',
  ],
  'PUT' => [
    '/books/:id' => 'App\Presentation\Controller\BookController@updateBook',
  ],
];
