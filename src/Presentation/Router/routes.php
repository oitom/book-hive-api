<?php

return [
  'POST' => [
    '/books' => 'App\Presentation\Controller\BookController@createBook',
  ],
  'GET' => [
    '/books' => 'App\Presentation\Controller\BookController@listBooks',
  ],
];
