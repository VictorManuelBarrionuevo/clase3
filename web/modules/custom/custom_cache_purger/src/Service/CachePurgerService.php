<?php

namespace Drupal\custom_cache_purger\Service;

class CachePurgerService
{
    /**
     * The CloudFront distribution ID.
     *
     * @var string|null
     */
    protected $distributionId;

    /**
     * Constructor to initialize the service with a distribution ID.
     *
     * @param string|null $distributionId
     *   The CloudFront distribution ID, if available. If not provided, a warning
     *   will be logged and a default value will be used.
     */
    public function __construct($distributionId = NULL)
    {
        if ($distributionId) {
            $this->distributionId = $distributionId;
        } else {
            //\Drupal::logger('custom_cache_purger')->warning('CloudFront Distribution ID not provided. Caching functionality will not work as expected.');
            $this->distributionId = 'default-distribution-id';
        }
    }

    /**
     * Purges the cache for a given URL.
     *
     * @param string $url
     *   The URL for which the cache needs to be purged.
     */
    public function purgeCache($url)
    {
        // Check if a valid distribution ID is provided before attempting to purge.
        if ($this->distributionId !== 'default-distribution-id') {
            try {
                // Add the actual cache purging logic here if needed.
                \Drupal::logger('custom_cache_purger')->notice('Purging cache for URL: ' . $url);
            } catch (\Exception $e) {
                // Log an error if the cache purge fails.
                \Drupal::logger('custom_cache_purger')->error('Failed to purge cache: ' . $e->getMessage());
            }
        } else {
            // Log a warning if the cache purge is skipped due to a missing distribution ID.
            \Drupal::logger('custom_cache_purger')->warning('Cache purge skipped because Distribution ID is missing.');
        }
    }
}
