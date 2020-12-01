<?php

namespace Drupal\pss_pse\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\pss_pse\Service\ApiTariferService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PSEForm.
 */
class PSEForm extends FormBase {

  /**
   * @var \Drupal\pss_pse\Service\ApiTariferService
   */
  protected $apiTarifer;

  /**
   * Class constructor.
   *
   * @param \Drupal\pss_pse\Service\ApiTariferService $apiTarifer
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
    return 'pse_form';
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

    $form['CONJOINT_DATE_NAISSANCE'] = [
      '#type' => 'date',
      '#title' => $this->t('Conjoint date de naissance'),
      '#weight' => '0',
      '#default_value' => '1983-11-18',
      '#required' => TRUE,
      '#states' => [
        'visible' => [
          ':input[name="PROTECTION_CONJOINT"]' => ['value' => 1],
        ],
      ],
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
        '0' => '0',
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
      ],
      '#weight' => '0',
      '#default_value' => '0',
      '#required' => TRUE,
      '#states' => [
        'visible' => [
          ':input[name="PROTECTION_ENFANTS"]' => ['value' => 1],
        ],
      ],
    ];

    $form['ENFANT_DATE_NAISSANCE_1'] = [
      '#type' => 'date',
      '#title' => $this->t('Enfant 1 date de naissance'),
      '#weight' => '0',
      '#default_value' => '2012-05-20',
      '#required' => TRUE,
      '#states' => [
        'visible' => [
          [':input[name="NOMBRE_ENFANTS"]' => ['value' => '1']],
          'xor',
          [':input[name="NOMBRE_ENFANTS"]' => ['value' => '2']],
          'xor',
          [':input[name="NOMBRE_ENFANTS"]' => ['value' => '3']],
          'xor',
          [':input[name="NOMBRE_ENFANTS"]' => ['value' => '4']],
          'xor',
          [':input[name="NOMBRE_ENFANTS"]' => ['value' => '5']],
        ],
      ],
    ];

    $form['ENFANT_DATE_NAISSANCE_2'] = [
      '#type' => 'date',
      '#title' => $this->t('Enfant 2 date de naissance'),
      '#weight' => '0',
      '#default_value' => '2012-05-20',
      '#required' => TRUE,
      '#states' => [
        'visible' => [
          [':input[name="NOMBRE_ENFANTS"]' => ['value' => '2']],
          'xor',
          [':input[name="NOMBRE_ENFANTS"]' => ['value' => '3']],
          'xor',
          [':input[name="NOMBRE_ENFANTS"]' => ['value' => '4']],
          'xor',
          [':input[name="NOMBRE_ENFANTS"]' => ['value' => '5']],
        ],
      ],
    ];

    $form['ENFANT_DATE_NAISSANCE_3'] = [
      '#type' => 'date',
      '#title' => $this->t('Enfant 3 date de naissance'),
      '#weight' => '0',
      '#default_value' => '2012-05-20',
      '#required' => TRUE,
      '#states' => [
        'visible' => [
          [':input[name="NOMBRE_ENFANTS"]' => ['value' => '3']],
          'xor',
          [':input[name="NOMBRE_ENFANTS"]' => ['value' => '4']],
          'xor',
          [':input[name="NOMBRE_ENFANTS"]' => ['value' => '5']],
        ],
      ],
    ];

    $form['ENFANT_DATE_NAISSANCE_4'] = [
      '#type' => 'date',
      '#title' => $this->t('Enfant 4 date de naissance'),
      '#weight' => '0',
      '#default_value' => '2012-05-20',
      '#required' => TRUE,
      '#states' => [
        'visible' => [
          [':input[name="NOMBRE_ENFANTS"]' => ['value' => '4']],
          'xor',
          [':input[name="NOMBRE_ENFANTS"]' => ['value' => '5']],
        ],
      ],
    ];

    $form['ENFANT_DATE_NAISSANCE_5'] = [
      '#type' => 'date',
      '#title' => $this->t('Enfant 5 date de naissance'),
      '#weight' => '0',
      '#default_value' => '2012-05-20',
      '#required' => TRUE,
      '#states' => [
        'visible' => [
          ':input[name="NOMBRE_ENFANTS"]' => ['value' => '5'],
        ],
      ],
    ];

    $form['REGIME_OBLIGATOIRE'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Regime obligatoire'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#default_value' => 'RG',
      '#required' => TRUE,
    ];

    $form['CODE_POSTAL'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Code postal'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#default_value' => '75015',
      '#required' => TRUE,
    ];

    $form['CODE_NIVEAU_PSE'] = [
      '#type' => 'select',
      '#title' => $this->t('Code niveau PSE'),
      '#options' => [
        'NIVEAU_PRO_1_1' => 'NIVEAU_PRO_1_1',
        'NIVEAU_PRO_1_2' => 'NIVEAU_PRO_1_2',
        'NIVEAU_PRO_1_3' => 'NIVEAU_PRO_1_3',
        'NIVEAU_PRO_2_1' => 'NIVEAU_PRO_2_1',
        'NIVEAU_PRO_2_2' => 'NIVEAU_PRO_2_2',
        'NIVEAU_PRO_2_3' => 'NIVEAU_PRO_2_3',
        'NIVEAU_PRO_3_2' => 'NIVEAU_PRO_3_2',
        'NIVEAU_PRO_3_3' => 'NIVEAU_PRO_3_3',
        'NIVEAU_PRO_3_4' => 'NIVEAU_PRO_3_4',
        'NIVEAU_PRO_4_4' => 'NIVEAU_PRO_4_4',
      ],
      '#weight' => '0',
      '#default_value' => 'NIVEAU_PRO_1_1',
      '#multiple' => FALSE,
      '#required' => TRUE,
    ];

    $form['CODE_PH'] = [
      '#type' => 'select',
      '#title' => $this->t('Code PH'),
      '#options' => [
        '0' => '0',
        '15' => '15',
        '30' => '30',
        '45' => '45',
      ],
      '#weight' => '0',
      '#default_value' => 45,
      '#required' => TRUE,
    ];

    $form['STRUCTURE_COTISATION'] = [
      '#type' => 'select',
      '#title' => $this->t('Structure cotisation'),
      '#weight' => '0',
      '#options' => [
        'TNS_STRUCTURE_UNIQUE' => 'TNS_STRUCTURE_UNIQUE',
        'TNS_STRUCTURE_ADULTE_ENFANT' => 'TNS_STRUCTURE_ADULTE_ENFANT',
      ],
      '#default_value' => 'TNS_STRUCTURE_UNIQUE',
      '#required' => TRUE,
    ];

    $form['REDUCTION_TNS'] = [
      '#type' => 'radios',
      '#title' => $this->t('Reduction TNS'),
      '#weight' => '0',
      '#options' => [
        0 => 'Non',
        1 => 'Oui',
      ],
      '#default_value' => 0,
    ];

    $form['BUDGET_MALIN'] = [
      '#type' => 'radios',
      '#title' => $this->t('Budget malin'),
      '#weight' => '0',
      '#options' => [
        0 => 'Non',
        1 => 'Oui',
      ],
      '#default_value' => 0,
    ];

    $form['codeOffre'] = [
      '#type' => 'hidden',
      '#value' => "PSE",
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
    /* foreach ($form_state->getValues() as $key => $value) {
       // @TODO: Validate fields.
     }*/
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /*foreach ($form_state->getValues() as $key => $value) {
      \Drupal::messenger()->addMessage($key . ': ' . ($key === 'text_format' ? $value['value'] : $value));
    }*/
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
      '#markup' => json_encode($data),
    ];
    $renderArray['#prefix'] = '<div id="output">';
    $renderArray['#suffix'] = '</div>';

    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand('#output', $renderArray));

    return $response;
  }

}
