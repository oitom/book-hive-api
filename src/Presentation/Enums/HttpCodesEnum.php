<?php

namespace App\Presentation\Enums;

class HttpCodesEnum
{
  public const HTTP_OK = 200;
  public const HTTP_CREATED = 201;
  public const HTTP_BAD_REQUEST = 400;
  public const HTTP_UNAUTHORIZED = 401;
  public const HTTP_FORBIDDEN = 403;
  public const HTTP_NOT_FOUND = 404;
  public const HTTP_INTERNAL_SERVER_ERROR = 500;
  public const HTTP_BAD_GATEWAY = 502;
  public const HTTP_SERVICE_UNAVAILABLE = 503;
}
