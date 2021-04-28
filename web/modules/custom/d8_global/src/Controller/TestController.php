<?php

namespace Drupal\d8_global\Controller;

use Drupal;
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
  public function test() {

    $build = [];

    $build['#title'] = 'TITRE';
    $build['#attributes'] = [
      'class' => ['announcement'],
    ];

    $build['announcement'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['announcement__content']],
      'wrapper' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['wrapper_content']],
        'content' => [
          '#type' => 'processed_text',
          '#text' => "<p>Mon text</p>",
          '#format' => "full_html",
        ],
      ],
    ];

    return $build;


    return [
      '#theme' => 'test_template',
      '#var' => $this->t('Test Value'),
      '#attached' => [
        'library' => ['d8_global/global-styling'],
      ],
    ];

  }

  public function sgEntityServices() {
    $sgEntityService = Drupal::service('sg_entity_services.service');
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


    $newEntity = Drupal::service('sg_entity_services.service')->getEntityStorageManager()->createEntity('node',
      [
        'type' => 'article',
        'title' => 'TaSoeur',
      ],
      $trans);

    /*$transEntity = Drupal::service('sg_entity_services.service')
    ->getEntityStorageManager()
    ->addTranslations(reset($entities['article']), $trans);*/

    //return;
  }

  public function deleteFiles() {
    $files = Drupal::entityTypeManager()->getStorage('file')->loadMultiple();
    foreach ($files as $file) {
      $file->delete();
    }
  }
}
