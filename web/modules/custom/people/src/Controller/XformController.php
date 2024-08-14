<?php

declare(strict_types=1);

namespace Drupal\people\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Returns responses for People routes.
 */
final class XformController extends ControllerBase
{
    /**
     * La conexión a la base de datos.
     *
     * @var \Drupal\Core\Database\Connection
     */
    protected $database;

    /**
     * La conexión a la base de datos.
     *
     * @var \Drupal\Core\Database\Connection
     */
    protected $database2;

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
     * Builds the response.
     */
    public function __invoke(Request $request)
    {
        $form_id = $request->request->get('form_id');
        $form_type = $request->request->get('form_type');
        $nombre = $request->request->get('nombre');
        $description = $request->request->get('description');
        $email = $request->request->get('email');
        $vehicle1 = $request->request->get('vehicle1');
        $vehicle2 = $request->request->get('vehicle2');
        $vehicle3 = $request->request->get('vehicle3');
        $pwd = $request->request->get('pwd');

        switch ($form_type) {
            case 'new':
                $query = "INSERT INTO {xform} (nombre, description, email, vehicle1, vehicle2, vehicle3, pwd)
                VALUES (:nombre, :description, :email, :vehicle1, :vehicle2, :vehicle3, :pwd)";
                $args = [
                    ':nombre' => $nombre,
                    ':description' => $description,
                    ':email' => $email,
                    ':vehicle1' => $vehicle1,
                    ':vehicle2' => $vehicle2,
                    ':vehicle3' => $vehicle3,
                    ':pwd' => $pwd,
                ];
                break;
            case 'edit':
                $query = "UPDATE {xform}
                  SET nombre = :nombre, description = :description, email = :email,
                      vehicle1 = :vehicle1, vehicle2 = :vehicle2, vehicle3 = :vehicle3, pwd = :pwd
                  WHERE id = :id";

                $args = [
                    ':nombre' => $nombre,
                    ':description' => $description,
                    ':email' => $email,
                    ':vehicle1' => $vehicle1,
                    ':vehicle2' => $vehicle2,
                    ':vehicle3' => $vehicle3,
                    ':pwd' => $pwd,
                    ':id' => $form_id
                ];
                break;
        }

        // Ejecutar la consulta.
        $this->database->query($query, $args);

        if(isset($form_id) & $form_id != '0'){
            \Drupal::messenger()->addWarning($this->t('The item with ID @id has been modified.', ['@id' => $form_id]));
        } else {
            $id = $this->database->lastInsertId();
            \Drupal::messenger()->addWarning($this->t('The item with ID @id has been created.', ['@id' => $id]));
        }

        $url = Url::fromRoute('people.listado')->toString();
        return new RedirectResponse($url);
    }
}
