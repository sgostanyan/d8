<?php

namespace Drupal\d8_global\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class AdimeoTestController.
 */
class AdimeoTestController extends ControllerBase {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->entityTypeManager = $container->get('entity_type.manager');
    return $instance;
  }

  /**
   * Main.
   *
   * @return string
   *   Return Hello string.
   */
  public function main() {


    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: main')
    ];
  }

}
