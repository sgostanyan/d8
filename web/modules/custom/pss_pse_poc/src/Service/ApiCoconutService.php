<?php

namespace Drupal\pss_pse_poc\Service;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;

/**
 * Class ApiCoconutService
 *
 * @package Drupal\pss_pse_poc\Service
 */
class ApiCoconutService {

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
   * ApiCoconutService constructor.
   *
   * @param $authParams
   * @param $apiParams
   * @param \GuzzleHttp\ClientInterface $client
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerChannelFactory
   */
  public function __construct($authParams, $apiParams, ClientInterface $client, LoggerChannelFactoryInterface $loggerChannelFactory) {
    $this->authParams = $authParams;
    $this->apiParams = $apiParams;
    $this->client = $client;
    $this->loggerChannelFactory = $loggerChannelFactory->get('PSS/PSE - ApiCoconut');
  }

  /**
   * @param array $params
   *
   * @return mixed|null
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getCCNs(array $params) {
    $token = $this->getToken();
    if ($token) {
      // Build Query.
      $query = '{';
      foreach ($params as $key => $param) {
        $query .= 'query' . $key . ': getCCNs(params: "' . $param . '") { id codeIdcc title lastUpdate listCodesNaf }';
      }
      $query .= '}';

      return $this->sendRequest($query, $token);
    }
  }

  /**
   * @return mixed|null
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  protected function getToken() {

    // Build URI.
    $uri = $this->authParams['scheme'] . '://';
    $uri .= $this->authParams['host'] . '/';
    $uri .= $this->authParams['basePath'];

    // Create request.
    try {

      $response = $this->client->request('POST',
        $uri,
        [
          'verify' => FALSE,
          'headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded',
          ],
          'form_params' => [
            'login' => 'hm-api',
            'password' => 'HM:aPi6-8CCnGQL',
          ],
        ]);

      // Response.
      if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
        $this->loggerChannelFactory->error($response->getStatusCode() . ' ' . $response->getReasonPhrase());
      }
      $result = json_decode($response->getBody()->getContents(), TRUE);
      return !empty($result['token']) ? $result['token'] : NULL;
    }

      // Error.
    catch (\Exception $e) {
      $this->loggerChannelFactory->error($e->getMessage());
    }

    return NULL;
  }

  /**
   * @param $graphQLquery
   * @param $token
   *
   * @return mixed|null
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  protected function sendRequest($graphQLquery, $token) {

    // Build URI.
    $uri = $this->apiParams['scheme'] . '://';
    $uri .= $this->apiParams['host'] . '/';
    $uri .= $this->apiParams['basePath'];

    // Request.
    try {

      // Sending.
      $response = $this->client->request('POST',
        $uri,
        [
          'verify' => FALSE,
          'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => $token,
          ],
          'body' => json_encode([
            'query' => $graphQLquery,
          ]),
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
