<?php


namespace Drupal\pss_pse\Service;


use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;

class ApiTariferService {

  const ENV = 'dev';

  protected $authParams;

  protected $apiParams;

  protected $client;

  protected $loggerChannelFactory;

  public function __construct($authParams, $apiParams, ClientInterface $client, LoggerChannelFactoryInterface $loggerChannelFactory) {
    $this->authParams = self::ENV == 'prod' ? $authParams['dev'] : $authParams['prod'];
    $this->apiParams = self::ENV == 'prod' ? $apiParams['dev'] : $apiParams['prod'];
    $this->client = $client;
    $this->loggerChannelFactory = $loggerChannelFactory->get('PSS/PSE - ApiTarifer');
  }

  protected function sendRequest(String $body, String $serviceType = 'indiv') {

    // Build URI.
    $uri = $this->apiParams['scheme'] . '/';
    $uri .= $this->apiParams['host'] . '/';
    $uri .= $serviceType == 'indiv' ? $this->apiParams['basePathIndiv'] : $this->apiParams['basePathColl'];

    // Request
    try {

      // Sending.
      $response = $this->client->request('POST',
        $uri,
        [
          'auth' => [
            $this->authParams['user'],
            $this->authParams['pwd'],
          ],
          'headers' => [
            'Content-Type' => 'application/json',
            'Apikey' => $this->authParams['apiKey'],
          ],
          'body' => [$body],
        ]);

      // Response.
      if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
        $this->loggerChannelFactory->error($response->getStatusCode() . ' ' . $response->getReasonPhrase());
      }
      return json_decode($response->getBody()->getContents(), TRUE);
    }

      // Error.
    catch (ClientException $e) {
      $this->loggerChannelFactory->error($e->getMessage());
    }
    return NULL;
  }

  public function send($body, $serviceType = 'indiv') {
    return $this->sendRequest(json_encode($body));
  }

}
