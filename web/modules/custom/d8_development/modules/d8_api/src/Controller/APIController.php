<?php

namespace Drupal\d8_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilderInterface;

/**
 * Class APIController.
 */
class APIController extends ControllerBase {

  /**
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  public function __construct(FormBuilderInterface $formBuilder) {
    $this->formBuilder = $formBuilder;
  }

  /**
   * @return array
   */
  public function graphqlquery() {
    return [
      '#theme' => 'd8_api_form',
      '#form' => $this->formBuilder->getForm('Drupal\d8_api\Form\GraphQLQueryForm'),
    ];
  }

  /**
   * @return array
   */
  public function siren() {
    return [
      '#theme' => 'd8_api_form',
      '#form' => $this->formBuilder->getForm('Drupal\d8_api\Form\ApiSireneForm'),
    ];
  }

  /**
   * @return array
   */
  public function tarifer() {
    return [
      '#theme' => 'd8_api_form',
      '#form' => $this->formBuilder->getForm('Drupal\d8_api\Form\TariferForm'),
    ];
  }

}
