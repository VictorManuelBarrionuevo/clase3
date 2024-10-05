<?php

namespace Drupal\varnish_alias_cache_purge\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\path_event_dispatcher\Event\Path\PathUpdateEvent;
use Drupal\path_event_dispatcher\PathHookEvents;
use Drupal\varnish_alias_cache_purge\Service\CachePurgerService;

/**
 * Event Subscriber to handle cache purging when a path alias is updated.
 */
class PathAliasSubscriber implements EventSubscriberInterface
{

    /**
     * The Cache Purger Service.
     *
     * @var \Drupal\varnish_alias_cache_purge\Service\CachePurgerService
     */
    protected $cachePurgerService;

    /**
     * Constructs a new PathAliasSubscriber.
     *
     * @param \Drupal\varnish_alias_cache_purge\Service\CachePurgerService $cache_purger_service
     *   The cache purger service.
     */
    public function __construct(CachePurgerService $cache_purger_service)
    {
        $this->cachePurgerService = $cache_purger_service;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        $ver = 1;
        print_r($ver);
        return [
            PathHookEvents::PATH_UPDATE => 'onPathAliasUpdate',
        ];
    }

    /**
     * Purges cache when a path alias is updated.
     *
     * @param \Drupal\path_event_dispatcher\Event\Path\PathUpdateEvent $event
     *   The event triggered when a path alias is updated.
     */
    public function onPathAliasUpdate(PathUpdateEvent $event)
    {
        $alias = $event->getAlias();
        $this->cachePurgerService->purgeAlias($alias);
    }
}
