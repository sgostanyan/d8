<?php

namespace Drupal\budge\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class ExportForm.
 */
class ExportForm extends FormBase {

  /**
   * Drupal\budge\Manager\BudgeExportManager definition.
   *
   * @var \Drupal\budge\Manager\BudgeExportManager
   */
  protected $budgeExportManager;

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Drupal\Core\File\FileSystem definition.
   *
   * @var \Drupal\Core\File\FileSystem
   */
  protected $fileSystem;

  /**
   * Drupal\Core\Messenger\Messenger.
   *
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->budgeExportManager = $container->get('budge.export.manager');
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->fileSystem = $container->get('file_system');
    $instance->messenger = $container->get('messenger');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'export_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Export'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $fileUri = $this->budgeExportManager->exportBudget();
    if ($fileUri) {
      $this->messenger->addMessage(t('Successfull exported'),
        Messenger::TYPE_STATUS);
      $response = new BinaryFileResponse($fileUri, 200, ['Content-Type' => 'application/yml', 'Content-Disposition' => 'attachment;filename="budge.yml"']);
      /*$response->setContentDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        'file_name.txt'
      );*/
      return $response->send();
    }
    else {
      $this->messenger->addMessage(t('An error occurred during processing'),
        Messenger::TYPE_ERROR);
    }
  }


}
