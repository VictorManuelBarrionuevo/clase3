<?php

namespace Drupal\custom_cache_purger\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\node\NodeInterface;
use Drupal\custom_cache_purger\Service\CachePurgerService;
use Drupal\core_event_dispatcher\Event\Entity\EntityInsertEvent;
use Drupal\core_event_dispatcher\Event\Entity\EntityUpdateEvent;
use Drupal\core_event_dispatcher\EntityHookEvents;

class NodeSaveSubscriber implements EventSubscriberInterface
{

    /**
     * Service to handle cache purging.
     *
     * @var \Drupal\custom_cache_purger\Service\CachePurgerService
     */
    protected $cachePurgerService;

    /**
     * Constructor to inject the CachePurgerService.
     *
     * @param \Drupal\custom_cache_purger\Service\CachePurgerService $cachePurgerService
     *   The service that handles cache purging.
     */
    public function __construct(CachePurgerService $cachePurgerService)
    {
        $this->cachePurgerService = $cachePurgerService;
    }

    /**
     * {@inheritdoc}
     *
     * Subscribe to node insert and update events.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EntityHookEvents::ENTITY_INSERT => 'onNodeInsert',
            EntityHookEvents::ENTITY_UPDATE => 'onNodeUpdate',
        ];
    }

    /**
     * Handles node insert events.
     *
     * @param \Drupal\core_event_dispatcher\Event\Entity\EntityInsertEvent $event
     *   The entity insert event.
     */
    public function onNodeInsert(EntityInsertEvent $event): void
    {
        $entity = $event->getEntity();
        // Ensure the entity is a node before proceeding.
        if ($entity instanceof NodeInterface) {
            \Drupal::logger('custom_cache_purger')->notice('Node insert event triggered for node ID: ' . $entity->id());
            $this->handleCachePurge($entity);
        }
    }

    /**
     * Handles node update events.
     *
     * @param \Drupal\core_event_dispatcher\Event\Entity\EntityUpdateEvent $event
     *   The entity update event.
     */
    public function onNodeUpdate(EntityUpdateEvent $event): void
    {
        $entity = $event->getEntity();
        // Ensure the entity is a node before proceeding.
        if ($entity instanceof NodeInterface) {
            \Drupal::logger('custom_cache_purger')->notice('Node update event triggered for node ID: ' . $entity->id());
            $this->handleCachePurge($entity);
        }
    }

    /**
     * Handles the cache purge logic.
     *
     * @param \Drupal\node\NodeInterface $node
     *   The node entity for which the cache needs to be purged.
     */
    protected function handleCachePurge(NodeInterface $node): void
    {
        // Only purge cache if the node has a canonical URL.
        if ($node->hasLinkTemplate('canonical')) {
            $url = $node->toUrl('canonical', ['absolute' => true])->toString();
            \Drupal::logger('custom_cache_purger')->notice('Purging cache for URL: ' . $url);
            $this->cachePurgerService->purgeCache($url);
        } else {
            \Drupal::logger('custom_cache_purger')->warning('Node ID: ' . $node->id() . ' does not have a canonical URL.');
        }
    }
}
