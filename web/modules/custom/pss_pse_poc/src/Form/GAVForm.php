<?php

namespace Drupal\pss_pse_poc\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\pss_pse_poc\Service\ApiTariferService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class GAVForm.
 */
class GAVForm extends FormBase {

  /**
   * @var \Drupal\pss_pse\Service\ApiTariferService
   */
  protected $apiTarifer;

  /**
   * Class constructor.
   *
   * @param \Drupal\pss_pse_poc\Service\ApiTariferService $apiTarifer
   */
  public function __construct(ApiTariferService $apiTarifer) {
    $this->apiTarifer = $apiTarifer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('pss_pse.api_tarifer'));
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'gav_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['PROSPECT_DATE_NAISSANCE'] = [
      '#type' => 'date',
      '#title' => $this->t('Date de naissance'),
      '#weight' => '0',
      '#default_value' => '1981-07-01',
      '#required' => TRUE,
    ];

    $form['PROTECTION_CONJOINT'] = [
      '#type' => 'radios',
      '#title' => $this->t('Protection conjoint'),
      '#weight' => '0',
      '#options' => [
        0 => 'Non',
        1 => 'Oui',
      ],
      '#default_value' => 0,
    ];

    $form['PROTECTION_ENFANTS'] = [
      '#type' => 'radios',
      '#title' => $this->t('Protection enfants'),
      '#weight' => '0',
      '#options' => [
        0 => 'Non',
        1 => 'Oui',
      ],
      '#default_value' => 0,
    ];

    $form['NOMBRE_ENFANTS'] = [
      '#type' => 'select',
      '#title' => $this->t('Nombre d\'enfants'),
      '#options' => [
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
      ],
      '#weight' => '0',
      '#default_value' => '1',
      '#required' => TRUE,
      '#states' => [
        'visible' => [
          ':input[name="PROTECTION_ENFANTS"]' => ['value' => 1],
        ],
      ],
    ];

    $form['codeOffre'] = [
      '#type' => 'hidden',
      '#value' => "HGAV",
    ];

    $form['dateEffet'] = [
      '#type' => 'hidden',
      '#value' => date("Y-m-d\TH:i:s.000\Z"),
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
    $data = !empty($values) ? $this->apiTarifer->send($values, 'indiv') : NULL;

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
