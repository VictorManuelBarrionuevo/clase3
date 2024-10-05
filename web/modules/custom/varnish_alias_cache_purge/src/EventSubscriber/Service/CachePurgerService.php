<?php

namespace Drupal\varnish_alias_cache_purge\Service;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\purge\Plugin\Purge\Purger\PluginManager;

/**
 * Service to handle cache purging for aliases in Varnish.
 */
class CachePurgerService
{

    /**
     * The Purger Plugin Manager service.
     *
     * @var \Drupal\purge\Plugin\Purge\Purger\PluginManager
     */
    protected $purgerManager;

    /**
     * The logger channel factory.
     *
     * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
     */
    protected $loggerFactory;

    /**
     * Constructs a new CachePurgerService.
     *
     * @param \Drupal\purge\Plugin\Purge\Purger\PluginManager $purger_manager
     *   The Purger Plugin Manager service.
     * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
     *   The logger channel factory.
     */
    public function __construct(PluginManager $purger_manager, LoggerChannelFactoryInterface $logger_factory)
    {
        $this->purgerManager = $purger_manager;
        $this->loggerFactory = $logger_factory;
    }

    /**
     * Purges the cache for a given alias.
     *
     * @param string $alias
     *   The path alias to purge.
     */
    public function purgeAlias($alias)
    {
        try {
            // Load the Varnish purger plugin.
            /** @var \Drupal\purge\Plugin\Purge\Purger\PurgerInterface $purger */
            $purger = $this->purgerManager->createInstance('varnish');

            // Invalidate the cache by creating an invalidation for the alias.
            $purger->invalidate(['url' => $alias]);

            $this->loggerFactory->get('varnish_alias_cache_purge')->info('Cache purged for alias: @alias', ['@alias' => $alias]);
        } catch (\Exception $e) {
            $this->loggerFactory->get('varnish_alias_cache_purge')->error('Cache purging failed: @message', ['@message' => $e->getMessage()]);
        }
    }
}
