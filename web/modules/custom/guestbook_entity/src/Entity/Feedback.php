<?php

namespace Drupal\guestbook_entity\Entity;

use Drupal\Core\Entity\Annotation\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the feedback entity class.
 *
 * @ContentEntityType(
 *   id = "kay_feedback",
 *   label = @Translation("Feedback"),
 *   label_collection = @Translation("Feedbacks"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\guestbook_entity\FeedbackListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\guestbook_entity\FeedbackAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\guestbook_entity\Form\FeedbackForm",
 *       "edit" = "Drupal\guestbook_entity\Form\FeedbackForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "kay_feedback",
 *   data_table = "kay_feedback_field_data",
 *   admin_permission = "administer feedback",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *   },
 *   links = {
 *     "canonical" = "/kay-feedback/{kay_feedback}",
 *     "add-form" = "/admin/content/kay-feedback/add",
 *     "edit-form" = "/admin/content/kay-feedback/{kay_feedback}/edit",
 *     "delete-form" = "/admin/content/kay-feedback/{kay_feedback}/delete",
 *     "collection" = "/admin/content/kay-feedback"
 *   },
 *   field_ui_base_route = "entity.kay_feedback.settings"
 * )
 */
class Feedback extends ContentEntityBase {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['avatar'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Your photo:'))
      ->setDefaultValue(NULL)
      ->setSettings([
        'file_extensions' => 'png jpg jpeg',
        'file_directory' => 'guestbook_entity/avatars',
        'max_filesize' => 2097152,
        'alt_field' => FALSE,
      ])
      ->setDisplayOptions('form', [
        'type' => 'image_image',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'image',
        'settings' => [
          'image_style' => 'thumbnail',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Your name:'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 100)
      ->setPropertyConstraints('value', [
        'Length' => [
          'min' => 2,
          'minMessage' => 'Minimum name length %limit characters.',
          'max' => 100,
          'maxMessage' => 'Maximum name length %limit characters.',
        ],
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Your email:'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'email_default',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'email_mailto',
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['phone'] = BaseFieldDefinition::create('telephone')
      ->setLabel(t('Your phone:'))
      ->setRequired(TRUE)
      ->addPropertyConstraints(
        'value', [
          'Regex' => [
            'pattern' => '/^\d{9}$/',
            'message' => t('Enter a phone number in the format 99 123 4568, without +380.'),
          ],
        ]
      )
      ->setSetting('max_length', 9)
      ->setDisplayOptions('form', [
        'type' => 'telephone_default',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'telephone_link',
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['message'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Message:'))
      ->setRequired(TRUE)
      ->addPropertyConstraints('value', [
        'Length' => [
          'max' => 1000,
          'maxMessage' => 'Maximum message length %limit characters.',
        ],
      ])
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'text_default',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['attachment'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Attachment:'))
      ->setDefaultValue(NULL)
      ->setSettings([
        'file_extensions' => 'png jpg jpeg',
        'file_directory' => 'guestbook_entity/images',
        'max_filesize' => 5242880,
        'alt_field' => FALSE,
      ])
      ->setDisplayOptions('form', [
        'type' => 'image_image',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'image',
        'label' => 'hidden',
        'settings' => [
          'image_style' => 'thumbnail',
          'image_link' => 'file',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);


    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on:'))
      ->setDescription(t('The time that the feedback was created.'))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'datetime_custom',
        'settings' => [
          'data_format' => 'm/j/Y H:i:s',
        ],
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
