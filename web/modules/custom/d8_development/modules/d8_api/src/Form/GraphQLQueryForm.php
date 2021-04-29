<?php

namespace Drupal\d8_api\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\d8_api\Service\ApiGraphQLQueryService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class GraphQLQueryForm
 *
 * @package Drupal\d8_api\Form
 */
class GraphQLQueryForm extends FormBase {

  /**
   * @var \Drupal\d8_api\Service\ApiGraphQLQueryService
   */
  protected $apiGraphQLQuery;

  public function __construct(ApiGraphQLQueryService $apiGraphQLQuery) {
    $this->apiGraphQLQuery = $apiGraphQLQuery;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('d8_api.graphqlquery'));
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'api_graphqlquery_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['naf'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Code NAF'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#default_value' => '6312Z',
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
    $data = !empty($values) ? $this->apiGraphQLQuery->getCCNs([$values['naf']]) : NULL;

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
