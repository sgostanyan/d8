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
   * @param $data
   * @param string $serviceType
   *   Can be 'indiv' or 'coll'.
   *
   * @return mixed|null
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function send(array $data, $serviceType = 'indiv') {
    // Build data to send.
    $builtData = [
      'codeOffre' => $data['codeOffre'],
      'dateEffet' => $data['dateEffet'],
      'donneeTarifantes' => [],
    ];
    foreach ($data as $key => $value) {
      if (!empty(Mapping::DATA_TYPE[$key])) {
        $builtData['donneeTarifantes'][] = [
          'cle' => $key,
          'dataType' => Mapping::DATA_TYPE[$key],
          'valeur' => $this->prepareValue($key, $value),
        ];
      }
    }
    return $this->sendRequest($builtData);
  }

  /**
   * @param $key
   * @param $value
   *
   * @return bool|string
   */
  protected function prepareValue($key, $value) {

    switch (Mapping::DATA_TYPE[$key]) {
      case 'DATE':
        return implode("/", array_reverse(explode('-', $value)));
        break;
      case 'BOOLEAN':
        return $value == 0 ? "false" : "true";
        break;
    }
    return $value;
  }

  /**
   * @param array $data
   * @param string $serviceType
   *
   * @return mixed|null
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  protected function sendRequest(array $data, string $serviceType = 'indiv') {

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
          'json' => $data,
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
