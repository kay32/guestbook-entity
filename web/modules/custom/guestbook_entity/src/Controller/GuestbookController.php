<?php

namespace Drupal\guestbook_entity\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Guestbook routes.
 */
class GuestbookController extends ControllerBase {

  /**
   * Class constructor.
   */
  public function __construct(EntityFormBuilderInterface $entityFormBuilder, EntityTypeManagerInterface $entityTypeManager) {
    $this->entityFormBuilder = $entityFormBuilder;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): GuestbookController {
    return new static(
      $container->get('entity.form_builder'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * Builds the response.
   */
  public function build(): array {
    $storage = $this->entityTypeManager->getStorage('kay_feedback');
    $form = $this->entityFormBuilder->getForm($storage->create(), 'add');

    $query = $storage->getQuery()
      ->sort('created', 'DESC')
      ->pager(5)
      ->execute();
    $feedbacks = $storage->loadMultiple($query);
    $feedbacks = $this->entityTypeManager
      ->getViewBuilder('kay_feedback')
      ->viewMultiple($feedbacks);

    return [
      '#theme' => 'kay_guestbook',
      '#feedback_list' => [
        '#theme' => 'kay_feedback_list',
        '#feedbacks' => $feedbacks,
        '#pager' => [
          '#type' => 'pager',
        ],
      ],
      '#form' => $form,
    ];
  }

}
