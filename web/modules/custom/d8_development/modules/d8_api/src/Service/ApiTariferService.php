<?php

namespace Drupal\d8_api\Service;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;

/**
 * Class ApiTariferService
 *
 * @package Drupal\d8_api\Service
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
    $this->loggerChannelFactory = $loggerChannelFactory->get('D8 API - ApiTarifer');
  }

  /**
   * @param array $data
   * @param string $serviceType
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

    $prepraredData = $this->prepareData($data);

    foreach ($prepraredData as $key => $value) {

      // If field is ENFANT_DATE_NAISSANCE, remove end index.
      $key = strpos($key, 'ENFANT_DATE_NAISSANCE') !== FALSE ? 'ENFANT_DATE_NAISSANCE' : $key;

      if (!empty(Mapping::DATA_TYPE[$key])) {
        $builtData['donneeTarifantes'][] = [
          'cle' => $key,
          'dataType' => Mapping::DATA_TYPE[$key],
          'valeur' => $value,
        ];
      }
    }

    return $this->sendRequest($builtData);
  }

  /**
   * @param $data
   *
   * @return mixed
   */
  protected function prepareData($data) {

    // Protection conjoint.
    if (empty($data['PROTECTION_CONJOINT'])) {
      unset($data['CONJOINT_DATE_NAISSANCE']);
    }

    // Protection enfants.
    if (!empty($data['PROTECTION_ENFANTS'])) {
      $nbEnfants = $data['NOMBRE_ENFANTS'];
      for ($i = $nbEnfants + 1; $i <= 5; $i++) {
        unset($data['ENFANT_DATE_NAISSANCE_' . $i]);
      }
    }
    else {
      unset($data['NOMBRE_ENFANTS']);
      unset($data['ENFANT_DATE_NAISSANCE_1']);
      unset($data['ENFANT_DATE_NAISSANCE_2']);
      unset($data['ENFANT_DATE_NAISSANCE_3']);
      unset($data['ENFANT_DATE_NAISSANCE_4']);
      unset($data['ENFANT_DATE_NAISSANCE_5']);
    }

    // Clean general field's value.
    foreach ($data as $key => $value) {
      switch (Mapping::DATA_TYPE[$key]) {
        case 'BOOLEAN':
          $data[$key] = $value == 0 ? "false" : "true";
          break;
      }
    }
    return $data;
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

    // Request.
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
