<?php

declare(strict_types=1);

namespace Drupal\people;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for the people data entity type.
 */
final class PeopleDataListBuilder extends EntityListBuilder
{
    /**
     * {@inheritdoc}
     */
    public function buildHeader(): array
    {
        $header['id'] = $this->t('ID');
        $header['label'] = $this->t('Label');
        $header['status'] = $this->t('Status');
        return $header + parent::buildHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity): array
    {
        /** @var \Drupal\people\PeopleDataInterface $entity */
        $row['id'] = $entity->id();
        $row['label'] = $entity->toLink();
        $row['status'] = $entity->get('status')->value ? $this->t('Enabled') : $this->t('Disabled');
        return $row + parent::buildRow($entity);
    }

}
