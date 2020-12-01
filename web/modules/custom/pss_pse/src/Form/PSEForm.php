<?php

namespace Drupal\pss_pse\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class PSEForm.
 */
class PSEForm extends FormBase {

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
    ];

    $form['PROTECTION_CONJOINT'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Protection conjoint'),
      '#weight' => '0',
    ];

    $form['PROTECTION_ENFANTS'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Protection conjoint'),
      '#weight' => '0',
    ];

    $form['REGIME_OBLIGATOIRE'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Regime obligatoire'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#default_value' => 'RG',
    ];

    $form['CODE_POSTAL'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Code postal'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#default_value' => '75015',
    ];

    $form['CODE_NIVEAU_PSE'] = [
      '#type' => 'select',
      '#title' => $this->t('Code niveau PSE'),
      '#options' => [
        'NIVEAU_PRO_1_1',
        'NIVEAU_PRO_1_2',
        'NIVEAU_PRO_1_3',
        'NIVEAU_PRO_2_1',
        'NIVEAU_PRO_2_2',
        'NIVEAU_PRO_2_3',
        'NIVEAU_PRO_3_2',
        'NIVEAU_PRO_3_3',
        'NIVEAU_PRO_3_4',
        'NIVEAU_PRO_4_4',
      ],
      '#size' => 5,
      '#weight' => '0',
      '#default_value' => 0,
      '#multiple' => FALSE,
    ];

    $form['CODE_PH'] = [
      '#type' => 'number',
      '#title' => $this->t('Code PH'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#default_value' => 45,
    ];

    $form['STRUCTURE_COTISATION'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Structure cotisation'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#default_value' => 'TNS_STRUCTURE_UNIQUE',
    ];

    $form['REDUCTION_TNS'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Reduction TNS'),
      '#weight' => '0',
    ];

    $form['BUDGET_MALIN'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Budget malin'),
      '#weight' => '0',
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
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
  /* foreach ($form_state->getValues() as $key => $value) {
      \Drupal::messenger()->addMessage($key . ': ' . ($key === 'text_format' ? $value['value'] : $value));
    }*/
  }

}
