<?php

namespace Drupal\d8_api\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\d8_api\Service\ApiGraphQLQueryService;
use Drupal\d8_api\Service\ApiSireneService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ApiSireneForm
 *
 * @package Drupal\d8_api\Form
 */
class ApiSireneForm extends FormBase {

  /**
   * @var \Drupal\d8_api\Service\ApiSireneService
   */
  protected $apiSireneService;

  public function __construct(ApiSireneService $apiSireneService) {
    $this->apiSireneService = $apiSireneService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('d8_api.api_sirene'));
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'api_siren_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['siren'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Code SIREN'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#default_value' => '662051275',
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::loadOutput',
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function loadOutput(array &$form, FormStateInterface $form_state) {

    $values = $form_state->getValues();
    $data = !empty($values) ? $this->apiSireneService->getDataFromCode($values['siren']) : NULL;

    $renderArray = [
      '#markup' => '<pre>' . json_encode($data, JSON_PRETTY_PRINT) . '</pre>',
    ];
    $renderArray['#prefix'] = '<div id="output">';
    $renderArray['#suffix'] = '</div>';

    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand('#output', $renderArray));

    return $response;
  }

}
