<?php

namespace Drupal\d8_api\Service;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;

/**
 * Class ApiSireneService
 *
 * @package Drupal\d8_api\Service
 */
class ApiSireneService {

  /**
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $loggerChannelFactory;

  /**
   * ApiSireneService constructor.
   *
   * @param \GuzzleHttp\ClientInterface $client
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerChannelFactory
   */
  public function __construct(ClientInterface $client, LoggerChannelFactoryInterface $loggerChannelFactory) {
    $this->client = $client;
    $this->loggerChannelFactory = $loggerChannelFactory->get('D8 API - ApiSirene');
  }

  /**
   * @param $code
   *
   * @return mixed|null
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getDataFromCode($code) {
    $type = strlen(strval($code)) == 14 ? 'siret' : 'siren';
    return $this->sendRequest('v1/' . $type . '/' . $code);
  }

  /**
   * @param $endpoint
   *
   * @return mixed|null
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  protected function sendRequest($endpoint) {

    // Build URI.
    $uri = 'https://entreprise.data.gouv.fr/api/sirene/' . $endpoint;

    // Request.
    try {

      // Sending.
      $response = $this->client->request('GET',
        $uri,
        [
          'verify' => FALSE,
          'headers' => [
            'Content-Type' => 'application/json',
          ],
        ]);

      // Response.
      if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
        $this->loggerChannelFactory->error($response->getStatusCode() . ' ' . $response->getReasonPhrase());
      }
      return json_decode($response->getBody()->getContents(), TRUE);
    }

      // Error.
    catch (\Exception $e) {
      $this->loggerChannelFactory->error($e->getMessage());
    }

    return NULL;
  }

  /**
   * @param $name
   *
   * @return mixed|null
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getDataFromName($name) {
    return $this->sendRequest('v1/full_text/' . $name);
  }

}
