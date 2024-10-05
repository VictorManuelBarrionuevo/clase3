<?php

declare(strict_types=1);

namespace Drupal\drupal_entity_form\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\drupal_entity_form\PeopleInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the people entity class.
 *
 * @ContentEntityType(
 *   id = "drupal_entity_form_people",
 *   label = @Translation("People"),
 *   label_collection = @Translation("Peoples"),
 *   label_singular = @Translation("people"),
 *   label_plural = @Translation("peoples"),
 *   label_count = @PluralTranslation(
 *     singular = "@count peoples",
 *     plural = "@count peoples",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\drupal_entity_form\PeopleListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\drupal_entity_form\Form\PeopleForm",
 *       "edit" = "Drupal\drupal_entity_form\Form\PeopleForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "drupal_entity_form_people",
 *   admin_permission = "administer drupal_entity_form_people",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/people",
 *     "add-form" = "/people/add",
 *     "canonical" = "/people/{drupal_entity_form_people}",
 *     "edit-form" = "/people/{drupal_entity_form_people}/edit",
 *     "delete-form" = "/people/{drupal_entity_form_people}/delete",
 *     "delete-multiple-form" = "/admin/content/people/delete-multiple",
 *   },
 *   field_ui_base_route = "entity.drupal_entity_form_people.settings",
 * )
 */
final class People extends ContentEntityBase implements PeopleInterface
{
  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void
  {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array
  {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Full Name'))
      ->setRequired(true)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', true);

    $fields['documents'] = BaseFieldDefinition::create('file')
      ->setLabel(t('Attached Documents'))
      ->setSettings([
        'uri_scheme' => 'public',
        'file_directory' => '',
        'file_extensions' => 'png jpg jpeg pdf',
      ])
      ->setRevisionable(true)
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setDescription(t('The email address of the user.'))
      ->setRevisionable(true)
      ->setRequired(true)
      ->setSettings([
        'max_length' => 254,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'email_mailto',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'email_default',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', true)
      ->setDisplayConfigurable('form', true);

      $fields['date_of_birth'] = BaseFieldDefinition::create('datetime')
    ->setLabel(t('Date and Time'))
    ->setDescription(t('The date and time of the event.'))
    ->setSettings([
        'datetime_type' => 'datetime',
        'max_length' => 255, // Ajusta el valor según sea necesario
        'text_processing' => 0,
    ])
    ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'datetime_default',
        'weight' => 0,
    ])
    ->setDisplayOptions('form', [
        'type' => 'datetime_default',
        'weight' => 0,
    ])
    ->setDisplayConfigurable('view', true)
    ->setDisplayConfigurable('form', true);

    $fields['gender'] = BaseFieldDefinition::create('list_string')
    ->setLabel(t('Gender'))
    ->setDescription(t('The gender of the user.'))
    ->setSettings([
        'allowed_values' => [
            'male' => t('Male'),
            'female' => t('Female'),
            'other' => t('Other'),
            'prefer_not_to_say' => t('Prefer not to say'),
        ],
    ])
    ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
    ])
    ->setDisplayOptions('form', [
        'type' => 'options_select', // O 'options_buttons' si deseas mostrar botones de opción.
        'weight' => 0,
    ])
    ->setDisplayConfigurable('view', true)
    ->setDisplayConfigurable('form', true);

    $fields['address'] = BaseFieldDefinition::create('string_long')
    ->setLabel(t('Address'))
    ->setDescription(t('The address of the user.'))
    ->setSettings([
        'max_length' => 512, // Ajusta el valor según sea necesario.
        'text_processing' => 0,
    ])
    ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
    ])
    ->setDisplayOptions('form', [
        'type' => 'string_textarea', // Utiliza un área de texto para permitir la entrada de direcciones largas.
        'weight' => 0,
    ])
    ->setDisplayConfigurable('view', true)
    ->setDisplayConfigurable('form', true);

    $fields['phone_number'] = BaseFieldDefinition::create('list_string')
    ->setLabel(t('Phone Number'))
    ->setDescription(t('The phone number of the user.'))   
    ->setSettings([
        'allowed_values' => [
            'mobile' => t('Mobile'),
            'landline' => t('Landline'),
            'fax' => t('Fax'),
            'other' => t('Other'),
        ],
    ])
    ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
    ])
    ->setDisplayOptions('form', [
        'type' => 'options_select', // Para seleccionar el tipo de teléfono.
        'weight' => 0,
    ])
    ->setDisplayConfigurable('view', true)
    ->setDisplayConfigurable('form', true);

    /* $fields['country_of_residence'] = BaseFieldDefinition::create('entity_reference') 
    ->setLabel(t('Country of Residence'))
    ->setDescription(t('The country where the user resides.'))
    ->setSettings([
        'allowed_values' => [
            'usa' => t('United States'),
            'canada' => t('Canada'),
            'uk' => t('United Kingdom'),
            'germany' => t('Germany'),
            'france' => t('France'),
            'spain' => t('Spain'),
            'mexico' => t('Mexico'),
            'other' => t('Other'), // Opción para otros países.
        ],
    ])
    ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
    ])
    ->setDisplayOptions('form', [
        'type' => 'options_select', // Utiliza un campo de selección para elegir el país.
        'weight' => 0,
    ])
    ->setDisplayConfigurable('view', true)
    ->setDisplayConfigurable('form', true); */


        $fields['acceptance_of_terms_and_conditions'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Acceptance of terms and conditions'))
      ->setDefaultValue(false)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => true,
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => 0,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', true);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(true)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => false,
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => 0,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', true);

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'label' => 'above',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', true);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(self::class . '::getDefaultEntityOwner')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', true);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the people was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', true);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the people was last edited.'));

    return $fields;
  }
}
