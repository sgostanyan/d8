<?php

namespace Drupal\list_page\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ArticleListForm.
 */
class ArticleListForm extends FormBase {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Symfony\Component\HttpFoundation\RequestStack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->requestStack = $container->get('request_stack');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'article_list_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['type'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Type'),
      '#target_type' => 'taxonomy_term',
      '#selection_settings' => ['target_bundles' => ['type']],
      '#weight' => '0',
      '#default_value' => $this->getDefaultValue('type'),
    ];
    $form['country'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Pays'),
      '#target_type' => 'taxonomy_term',
      '#selection_settings' => ['target_bundles' => ['country']],
      '#weight' => '0',
      '#default_value' => $this->getDefaultValue('country'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * @param $queryKey
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function getDefaultValue($queryKey) {
    $id = $this->requestStack->getCurrentRequest()->query->get($queryKey);
    if ($id) {
      $entity = $this->entityTypeManager->getStorage('taxonomy_term')->load($id);
      return $entity ? $entity : '';
    }
    return '';
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
    $type = $form_state->getValue('type');
    $country = $form_state->getValue('country');
    $type ? $this->requestStack->getCurrentRequest()->query->set('type', $type) : '';
    $country ? $this->requestStack->getCurrentRequest()->query->set('type', $country) : '';
  }

  /**
   * @param array|null $conditions
   *  Array of array [fieldName, value, operator]
   * ex:
   * [
   *  ['field_category', ['34', '23', '22'], 'IN'],
   *  ['status', '1']
   *  ['changed', 'DESC'],
   * ]
   *
   *
   * @return array|void
   */
  public function buildConditions(array $conditions = []) {
    // Default condition.
    /*  $builtConditions = [
        [
          self::CONTENT_BUNDLE_FIELD,
          self::CONTENT_BUNDLE_IDS,
          'IN',
        ],
      ];
      // Extra conditions.
      foreach ($conditions as $condition) {
        $builtConditions[] = $condition;
      }
      return $builtConditions;*/
  }

}
