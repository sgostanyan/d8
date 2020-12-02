<?php

namespace Drupal\pss_pse\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilderInterface;

/**
 * Class POCController.
 */
class POCController extends ControllerBase {

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
  public function pse() {
    return [
      '#theme' => 'poc_form',
      '#form' => $this->formBuilder->getForm('Drupal\pss_pse\Form\PSEForm'),
    ];
  }

  /**
   * @return array
   */
  public function hspart() {
    return [
      '#theme' => 'poc_form',
      '#form' => $this->formBuilder->getForm('Drupal\pss_pse\Form\HSPARTForm'),
    ];
  }

  /**
   * @return array
   */
  public function gav() {
    return [
      '#theme' => 'poc_form',
      '#form' => $this->formBuilder->getForm('Drupal\pss_pse\Form\GAVForm'),
    ];
  }

}
