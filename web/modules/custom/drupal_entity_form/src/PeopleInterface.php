<?php

declare(strict_types=1);

namespace Drupal\drupal_entity_form;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a people entity type.
 */
interface PeopleInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
