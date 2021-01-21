<?php

namespace Drupal\d8_global\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class TestController.
 */
class TestController extends ControllerBase {

  /**
   * Test1.
   *
   * @return array Return Hello string.
   *   Return Hello string.
   */
  public function test1() {

    $result = 'TEST';

    /**
     * @var \Drupal\pss_pse_poc\Service\ApiCoconutService $coconut
     */
    $coconut = \Drupal::service('pss_pse.api_coconut');

    /**
     * @var \Drupal\pss_pse_poc\Service\ApiSireneService $sirene
     */
    $sirene = \Drupal::service('pss_pse.api_sirene');

    $result = $coconut->getCCNs(["6312Z", "7022Z"]);

    $result = $sirene->getDataFromCode("48098035800059");

    $result = $sirene->getDataFromName("Adimeo");

    include 'graphql.php';

   // \graphql::go();

 /*  // $this->testWS();
    $this->sgEntityServices();

    /**
     * @var \Drupal\sg_entity_services\Services\SgEntityServices $sgServices
     */
    //$sgServices = \Drupal::service('sg_entity_services.service');

    return [
      '#type' => 'markup',
      '#markup' => json_encode($result),
    ];
  }

  public function testWS() {
    $orphee = 'https://wsorphee.gironde.fr/serviceopac.asmx';
    $opac = 'https://wsopac.gironde.fr/service.asmx?WSDL';
    $cle = '82ba29e9906bad99122c9b68e5552ec8';

    $header = 'Host: wsorphee.gironde.fr
  Content-Type: text/xml; charset=utf-8
  Content-Length: length
  SOAPAction: "http://c3rb.org/GetToken"';

    $xml = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetToken xmlns="http://c3rb.org/">
      <key>string</key>
    </GetToken>
  </soap:Body>
</soap:Envelope>';

    try {
      $client = new \SoapClient($opac, [
        'Host' => 'wsorphee.gironde.fr',
        'Content-Type' => 'text/xml; charset=utf-8',
        'Content-Length' => 'length',
        'SOAPAction' => 'http://c3rb.org/GetToken',
      ]);
    }
    catch (\SoapFault $e) {
      ksm($e->getMessage());
      return;
    }


    //$client = new SoapClient("http://localhost/wsOpac/ServiceOpac.asmx?WSDL");

    //$client = new SoapClient($opac, ["key" => $cle]);
    //ksm($client);
    //$rslt = $client->__getToken(["key" => $cle]);
    //ksm($client->__getFunctions());
    //ksm($client->__soapCall('getToken', ["key" => $cle]));
    //$_SESSION['orphee_id'] = $rslt->GetTokenResult;
    //$client->__setCookie("ASP.NET_SessionId", $_SESSION['orphee_id']);

  }

  public function sgEntityServices() {
    $sgEntityService = \Drupal::service('sg_entity_services.service');
    /*$fid = $sgEntityService->getFileManager()->generateFileEntity('public://sources/', 'tiger.jpg', 'private://animals/');
     $fileInfo = $sgEntityService->getFileManager()->getFileInfos(281);
     $fileSize = $sgEntityService->getFileManager()->sanitizeFileSize(286567); //287 Ko
     $image = $sgEntityService->getEntityDisplayManager()->imageStyleRender(298, 'thumbnail', ['class' => ['thumb-style']]);
     $imageUrl = $sgEntityService->getImageManager()->getImageStyleUrl(298, 'thumbnail');
     $imageStyles = $sgEntityService->getImageManager()->getImageStyles();*/

/*     $entities = $sgEntityService->getEntityStorageManager()->getEntities('node', NULL, [4]);
     $viewModes = $sgEntityService->getEntityDisplayManager()->getViewModes('node');
     $renderArray = $sgEntityService->getEntityDisplayManager()->renderEntity(reset($entities['article']));
     $markup = $sgEntityService->getEntityDisplayManager()->renderArrayToMarkup($renderArray);
     $tag = $sgEntityService->getEntityDisplayManager()->htmlTagRender('a', 'TOTO', ['href' => 'http://top.com']);
     $markup = $sgEntityService->getEntityDisplayManager()->renderArrayToMarkup($tag);*/

     $trans = [
       'en' => [
         'title' => 'EN title',
         'body' => [
           'value' => 'English summary',
         ],
         'field_tags' => [
           [
             'target_id' => 1,
           ],
         ],
       ],
       'es' => [
         'title' => 'ES title',
         'body' => [
           'value' => 'Spanish summary',
         ],
         'field_tags' => [
           [
             'target_id' => 1,
           ],
         ],
       ],
     ];

     $fieldValues = [
       'title' => 'Title',
       'body' => [
         'value' => 'summary text',
       ],
       'field_tags' => [
         [
           'target_id' => 1,
         ],
       ],
     ];


    $newEntity = \Drupal::service('sg_entity_services.service')
       ->getEntityStorageManager()
       ->createEntity('node', [
         'type' => 'article',
         'title' => 'TaSoeur'], $trans);

    /*$transEntity = Drupal::service('sg_entity_services.service')
      ->getEntityStorageManager()
      ->addTranslations(reset($entities['article']), $trans);*/

    //return;
  }

  public function deleteFiles() {
    $files = \Drupal::entityTypeManager()->getStorage('file')->loadMultiple();
    foreach ($files as $file) {
      $file->delete();
    }
  }

}
