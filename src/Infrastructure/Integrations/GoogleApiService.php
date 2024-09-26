<?php

namespace App\Infrastructure\Integrations;

class GoogleApiService
{
  private $apiUrl;
  private $apiKey;
  private $maxResults;

  public function __construct()
  {
    $this->apiKey = getenv('GOOGLE_API_KEY');
    $this->apiUrl = getenv('GOOGLE_BOOK_API_URL');
    $this->maxResults = getenv('GOOGLE_BOOK_MAX_RESULTS');
  }

  public function searchBooks(string $query, array $params = [])
  {
    if (count($params) == 0)
      $params = ['maxResults' => $this->maxResults, 'orderBy'=> 'relevance'];

    $queryParams = http_build_query(array_merge(['q' => $query, 'key' => $this->apiKey], $params));
    $url = $this->apiUrl . '?' . $queryParams;

    $response = $this->makeRequest($url);

    if ($response === false) {
      return false;
    }
    return json_decode($response, true);
  }

  private function makeRequest(string $url)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($httpCode !== 200) {
      return false;
    }

    return $response;
  }
}
