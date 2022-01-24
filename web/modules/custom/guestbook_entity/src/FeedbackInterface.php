<?php

namespace Drupal\guestbook_entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a feedback entity type.
 */
interface FeedbackInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the feedback title.
   *
   * @return string
   *   Title of the feedback.
   */
  public function getTitle(): string;

  /**
   * Sets the feedback title.
   *
   * @param string $title
   *   The feedback title.
   *
   * @return \Drupal\guestbook_entity\FeedbackInterface
   *   The called feedback entity.
   */
  public function setTitle(string $title): FeedbackInterface;

  /**
   * Gets the feedback creation timestamp.
   *
   * @return int
   *   Creation timestamp of the feedback.
   */
  public function getCreatedTime(): int;

  /**
   * Sets the feedback creation timestamp.
   *
   * @param int $timestamp
   *   The feedback creation timestamp.
   *
   * @return \Drupal\guestbook_entity\FeedbackInterface
   *   The called feedback entity.
   */
  public function setCreatedTime(int $timestamp): FeedbackInterface;

  /**
   * Returns the feedback status.
   *
   * @return bool
   *   TRUE if the feedback is enabled, FALSE otherwise.
   */
  public function isEnabled(): bool;

  /**
   * Sets the feedback status.
   *
   * @param bool $status
   *   TRUE to enable this feedback, FALSE to disable.
   *
   * @return \Drupal\guestbook_entity\FeedbackInterface
   *   The called feedback entity.
   */
  public function setStatus($status): FeedbackInterface;

}
