<?php

namespace Drupal\guestbook_entity\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Form controller for the feedback entity edit forms.
 */
class FeedbackForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments =
      $message_arguments + [
        'link' => \Drupal::service('renderer')->render($link),
      ];

    if ($result == SAVED_NEW) {
      $this->messenger()
        ->addStatus($this->t('New feedback %label has been created.', $message_arguments));
      $this->logger('guestbook_entity')
        ->notice('Created new feedback %label', $logger_arguments);
    }
    else {
      $this->messenger()
        ->addStatus($this->t('The feedback %label has been updated.', $message_arguments));
      $this->logger('guestbook_entity')
        ->notice('Updated new feedback %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.kay_feedback.canonical', ['kay_feedback' => $entity->id()]);
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);
    $currentRoute = \Drupal::routeMatch()->getRouteName();
    if ($currentRoute == 'guestbook_entity.main') {
      $form['#prefix'] = '<div id="kay-feedback-form-wrapper">';
      $form['#suffix'] = '</div>';
      $form['actions']['submit']['#ajax'] = [
        'callback' => '::submitAjaxCallback',
        'wrapper' => 'kay-feedback-form-wrapper',
      ];
    }
    return $form;
  }

  /**
   * AJAX form submit handler.
   */
  public function submitAjaxCallback(array &$form, FormStateInterface $form_state) {
    if ($form_state->getErrors()) {
      return $form;
    }
    $redirectUrl = Url::fromRoute('guestbook_entity.main')->toString();
    $response = new AjaxResponse();
    $response->addCommand(new RedirectCommand($redirectUrl));
    return $response;
  }

}
