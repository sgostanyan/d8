<?php

namespace Drupal\custom_entity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SLAgencyEntityTypeForm.
 */
class SLAgencyEntityTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $sl_agency_entity_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $sl_agency_entity_type->label(),
      '#description' => $this->t("Label for the SL Agency type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $sl_agency_entity_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\custom_entity\Entity\SLAgencyEntityType::load',
      ],
      '#disabled' => !$sl_agency_entity_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $sl_agency_entity_type = $this->entity;
    $status = $sl_agency_entity_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label SL Agency type.', [
          '%label' => $sl_agency_entity_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label SL Agency type.', [
          '%label' => $sl_agency_entity_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($sl_agency_entity_type->toUrl('collection'));
  }

}
