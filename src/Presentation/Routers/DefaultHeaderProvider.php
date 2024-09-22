<?php

namespace App\Presentation\Routers;


class DefaultHeaderProvider implements HeaderProviderInterface
{
  public function getHeaders()
  {
    return function_exists('getallheaders') ? getallheaders() : [];
  }
}