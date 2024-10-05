<?php

namespace Drupal\simple_node_logger\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\node\NodeInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

class NodeSaveSubscriber implements EventSubscriberInterface
{

    protected $logger;

    // Constructor para inyectar el servicio de logging.
    public function __construct(LoggerChannelFactoryInterface $logger_factory)
    {
        $this->logger = $logger_factory->get('simple_node_logger');
    }

    // Método para subscribirse a los eventos.
    public static function getSubscribedEvents()
    {
        $events['node.insert'][] = ['onNodeSave', 0];
        $events['node.update'][] = ['onNodeSave', 0];
        return $events;
    }

    // Método que se ejecuta cuando se guarda un nodo.
    public function onNodeSave(EntityInterface $entity)
    {
        if ($entity instanceof NodeInterface) {
            $this->logger->info('Node saved with ID: @id', ['@id' => $entity->id()]);
        }
    }
}
