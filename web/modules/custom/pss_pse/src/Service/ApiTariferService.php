<?php

namespace Drupal\pss_pse\Service;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;

/**
 * Class ApiTariferService
 *
 * @package Drupal\pss_pse\Service
 */
class ApiTariferService {

  const ENV = 'dev';

  /**
   * @var mixed
   */
  protected $authParams;

  /**
   * @var mixed
   */
  protected $apiParams;

  /**
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $loggerChannelFactory;

  /**
   * ApiTariferService constructor.
   *
   * @param $authParams
   * @param $apiParams
   * @param \GuzzleHttp\ClientInterface $client
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerChannelFactory
   */
  public function __construct($authParams, $apiParams, ClientInterface $client, LoggerChannelFactoryInterface $loggerChannelFactory) {
    $this->authParams = self::ENV == 'prod' ? $authParams['prod'] : $authParams['dev'];
    $this->apiParams = self::ENV == 'prod' ? $apiParams['prod'] : $apiParams['dev'];
    $this->client = $client;
    $this->loggerChannelFactory = $loggerChannelFactory->get('PSS/PSE - ApiTarifer');
  }

  /**
   * @param $body
   * @param string $serviceType
   *   Can be 'indiv' or 'coll'.
   *
   * @return mixed|null
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function send($body, $serviceType = 'indiv') {
    return $this->sendRequest($body);
  }

  /**
   * @param array $body
   * @param string $serviceType
   *
   * @return mixed|null
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  protected function sendRequest(array $body, string $serviceType = 'indiv') {

    // Build URI.
    $uri = $this->apiParams['scheme'] . '://';
    $uri .= $this->apiParams['host'] . '/';
    $uri .= $serviceType == 'indiv' ? $this->apiParams['basePathIndiv'] : $this->apiParams['basePathColl'];

    // Create request.
    try {

      // Sending.
      $response = $this->client->request('POST',
        $uri,
        [
          'auth' => [$this->authParams['user'], $this->authParams['pwd']],
          'headers' => [
            'Apikey' => $this->authParams['apiKey'],
            'Content-Type' => 'application/json',
          ],
          'json' => $body,
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

}
