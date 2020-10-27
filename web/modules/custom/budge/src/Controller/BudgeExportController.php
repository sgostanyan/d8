<?php

namespace Drupal\budge\Controller;

use Drupal\budge\Manager\BudgeExportManager;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class BudgeExportController.
 */
class BudgeExportController extends ControllerBase {

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
   * BudgeExportController constructor.
   *
   * @param \Drupal\budge\Manager\BudgeExportManager $budgeExportManager
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\Core\File\FileSystemInterface $fileSystem
   * @param \Drupal\Core\Messenger\Messenger $messenger
   */
  public function __construct(BudgeExportManager $budgeExportManager, EntityTypeManagerInterface $entityTypeManager, FileSystemInterface $fileSystem, Messenger $messenger) {
    $this->budgeExportManager = $budgeExportManager;
    $this->entityTypeManager = $entityTypeManager;
    $this->fileSystem = $fileSystem;
    $this->messenger = $messenger;
  }

  /**
   * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\RedirectResponse
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function export() {
    $fileUri = $this->budgeExportManager->exportBudget();
    if ($fileUri) {
      $response = new BinaryFileResponse($fileUri, 200, [
        'Content-Type' => 'text/yaml',
        'Content-Disposition' => 'attachment;filename="budge.yml"',
      ]);
      return $response->send();
    }
    else {
      $this->messenger->addMessage(t('An error occurred during processing'), Messenger::TYPE_ERROR);
      return new RedirectResponse(Url::fromRoute('budge.export_main')->toString());
    }
  }

  /**
   * @return string[]
   */
  public function main() {
    return [
      '#theme' => 'budge_export',
    ];
  }

}
