<?php

/**
 * @file
 * Contains \Drupal\d8_queue\Form\NodePublisherQueueForm.
 */

namespace Drupal\d8_queue\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Queue\QueueFactory;
use Drupal\Core\Queue\QueueWorkerManagerInterface;
use Drupal\Core\Queue\SuspendQueueException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class NodePublisherQueueForm
 *
 * @package Drupal\d8_queue\Form
 */
class NodePublisherQueueForm extends FormBase {

  /**
   * @var QueueFactory
   */
  protected $queueFactory;

  /**
   * @var QueueWorkerManagerInterface
   */
  protected $queueManager;

  /**
   * @var \Drupal\Core\Queue\QueueInterface
   */
  protected $nodePublishQueue;

  /**
   * @var object
   */
  protected object $manualQueueWorker;

  /**
   * {@inheritdoc}
   */
  public function __construct(QueueFactory $queue, QueueWorkerManagerInterface $queue_manager) {
    $this->queueFactory = $queue;
    $this->queueManager = $queue_manager;
    $this->nodePublishQueue = $this->queueFactory->get('manual_node_publisher');
    $this->manualQueueWorker = $this->queueManager->createInstance('manual_node_publisher');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('queue'), $container->get('plugin.manager.queue_worker'));
  }

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'd8_queue_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['help'] = [
      '#type' => 'markup',
      '#markup' => $this->t('Submitting this form will process the Manual Queue which contains @number items.', ['@number' => $this->nodePublishQueue->numberOfItems()]),
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Process queue'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    while ($item = $this->nodePublishQueue->claimItem()) {
      try {
        $this->manualQueueWorker->processItem($item->data);
        $this->nodePublishQueue->deleteItem($item);
      }
      catch (SuspendQueueException $e) {
        $this->nodePublishQueue->releaseItem($item);
        break;
      }
      catch (\Exception $e) {
        watchdog_exception('d8_queue', $e);
      }
    }
  }
}
