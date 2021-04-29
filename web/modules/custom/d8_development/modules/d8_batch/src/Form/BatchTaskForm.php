<?php

namespace Drupal\d8_batch\Form;

use Drupal;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BatchTaskForm
 *
 * @package Drupal\d8_batch\Form
 */
class BatchTaskForm extends FormBase {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  /**
   * BatchTaskForm constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, MessengerInterface $messenger) {
    $this->entityTypeManager = $entityTypeManager;
    $this->messenger = $messenger;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Drupal\d8_batch\Form\BatchTaskForm|static
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('entity_type.manager'), $container->get('messenger'));
  }

  /**
   * @param $nid
   * @param $context
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public static function execute($nid, &$context) {
    $node = Drupal::entityTypeManager()->getStorage('node')->load($nid);
    $context['message'] = 'Processing - ' . $node->label();
    $context['results'][] = $node->label();
  }

  /**
   * Finish callback.
   *
   * @param mixed $success
   *   Success.
   * @param mixed $results
   *   Results.
   * @param mixed $operations
   *   Operations.
   */
  public static function finishedCallback($success, $results, $operations) {
    if ($success) {
      $message = Drupal::translation()->formatPlural(count($results),
        'One task processed.',
        '@count tasks processed.');
      Drupal::messenger()->addMessage($message);
    }
    else {
      Drupal::messenger()->addError(t('Finished with an error.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'batch_task_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['limit'] = [
      '#type' => 'number',
      '#min' => 1,
      '#default_value' => 1,
      '#required' => true,
      '#description' => $this->t('Limit of items'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Start'),
      '#description' => $this->t('Dummy batch task.'),
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

    $nids = $this->entityTypeManager->getStorage('node')->getQuery()->range(0, $form_state->getValue('limit'))->execute();

    $operations = [];

    foreach ($nids as $nid) {
      $operations[] = [
        'Drupal\d8_batch\Form\BatchTaskForm::execute',
        [$nid],
      ];
    }

    $batch = [
      'title' => t('D8 Batch task'),
      'operations' => $operations,
      'init_message' => t('Task creating process is starting.'),
      'progress_message' => t('Processed @current out of @total. Estimated time: @estimate.'),
      'error_message' => t('An error occurred during processing'),
      'finished' => '\Drupal\Drupal\d8_batch\Form\BatchTask::finishedCallback',
    ];

    $batch['operations'] = batch_set($batch);
  }

}
