<?php

declare(strict_types=1);

namespace Drupal\people\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for People routes.
 */
final class FormTestController extends ControllerBase
{
  /**
   * La conexión a la base de datos.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   La conexión a la base de datos.
   */
  public function __construct(Connection $database)
  {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Builds the response.
   */
  public function __invoke(Request $request): array
  {
    $nombre = $request->request->get('nombre');
    $email = $request->request->get('email');

    // Validar las variables.
    if (empty($nombre) || empty($email)) {
      return new Response('Nombre y Email son requeridos.', 400);
    }

    $query = "INSERT INTO {test_form} (nombre, email) VALUES (:nombre, :email)";
    $args = [':nombre' => $nombre, ':email' => $email];

    // Ejecutar la consulta.
    $this->database->query($query, $args);

    /*$this->database->insert('test_form')
      ->fields([
        'nombre' => $nombre,
        'email' => $email,
      ])
      ->execute();*/

    //return new Response('Datos guardados exitosamente.');

    //test_form
    return [
      '#type' => 'markup',
      '#markup' => $this->t('El valor nombre es: @nombre', ['@nombre' => $nombre]),
    ];
  }
}
