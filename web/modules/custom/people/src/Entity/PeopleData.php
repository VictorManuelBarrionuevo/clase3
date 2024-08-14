<?php

declare(strict_types=1);

namespace Drupal\people\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\people\PeopleDataInterface;

/**
 * Defines the people data entity class.
 *
 * @ContentEntityType(
 *   id = "people_entity",
 *   label = @Translation("People data"),
 *   label_collection = @Translation("People datas"),
 *   label_singular = @Translation("people data"),
 *   label_plural = @Translation("people datas"),
 *   label_count = @PluralTranslation(
 *     singular = "@count people datas",
 *     plural = "@count people datas",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\people\PeopleDataListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\people\PeopleDataAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\people\Form\PeopleDataForm",
 *       "edit" = "Drupal\people\Form\PeopleDataForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "people_entity",
 *   admin_permission = "administer people_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/entity",
 *     "add-form" = "/people-data/add",
 *     "canonical" = "/people-data/{people_entity}",
 *     "edit-form" = "/people-data/{people_entity}/edit",
 *     "delete-form" = "/people-data/{people_entity}/delete",
 *     "delete-multiple-form" = "/admin/content/entity/delete-multiple",
 *   },
 *   field_ui_base_route = "entity.people_entity.settings",
 * )
 */
final class PeopleData extends ContentEntityBase implements PeopleDataInterface
{
    /**
     * {@inheritdoc}
     */
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array
    {

        $fields = parent::baseFieldDefinitions($entity_type);

        $fields['label'] = BaseFieldDefinition::create('string')
          ->setLabel(t('Label'))
          ->setRequired(true)
          ->setSetting('max_length', 255)
          ->setDisplayOptions('form', [
            'type' => 'string_textfield',
          ])
          ->setDisplayConfigurable('form', true)
          ->setDisplayOptions('view', [
            'label' => 'hidden',
            'type' => 'string',
          ])
          ->setDisplayConfigurable('view', true);

        $fields['description'] = BaseFieldDefinition::create('text_long')
          ->setLabel(t('Description'))
          ->setDisplayOptions('form', [
            'type' => 'text_textarea',
          ])
          ->setDisplayConfigurable('form', true)
          ->setDisplayOptions('view', [
            'type' => 'text_default',
            'label' => 'above',
          ])
          ->setDisplayConfigurable('view', true);

        $fields['hair_color'] = BaseFieldDefinition::create('string')
          ->setLabel(t('Hair Color'))
          ->setSetting('max_length', 255)
          ->setDisplayOptions('form', [
            'type' => 'string_textfield',
          ])
          ->setDisplayConfigurable('form', true)
          ->setDisplayOptions('view', [
            'label' => 'hidden',
            'type' => 'string',
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
          ])
          ->setDisplayConfigurable('form', true)
          ->setDisplayOptions('view', [
            'type' => 'boolean',
            'label' => 'above',

            'settings' => [
              'format' => 'enabled-disabled',
            ],
          ])
          ->setDisplayConfigurable('view', true);

        return $fields;
    }
}
