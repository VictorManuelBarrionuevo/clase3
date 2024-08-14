<?php

declare(strict_types=1);

namespace Drupal\people\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Returns responses for People routes.
 */
final class ListadoController extends ControllerBase
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
     * Builds the response.
     */
    public function __invoke(): array
    {
        //creo la sentencia a ejecutar
        $query = "SELECT * FROM {xform}";

        //Ejecuto la sentencia a la base de datos
        $rows = $this->database->query($query);

        //recorro los resultados para ponerlos en un array
        $items = [];
        foreach ($rows as $row) {
            $record_array = (array) $row;
            $record_array['delete'] = [
                'data' => [
                    '#type' => 'link',
                    '#title' => $this->t('Delete'),
                    '#url' => Url::fromRoute('people.listado_delete', ['id' => $record_array['id']]),
                    '#attributes' => [
                        'class' => ['deletebutton', 'button--danger'],
                    ],
                ],
            ];
            $items[] = $record_array;
        }

        $header = [];
        if (!empty($items)) {
            $header = array_keys((array) $items[0]);
        }

        // Estructura de render array para la tabla
        /*$build['content'] = [
            '#type' => 'table',
            '#header' => $header,
            '#rows' => $items,
            '#attributes' => [
                'class' => ['my-custom-table-class'],
            ],
        ];*/

        $build['people_tabla_listado'] = array(
            '#theme' => 'tabla_listado',
            '#people_header' => $header,
            '#people_rows' => $items,
        );

        return $build;
    }

    public function delete($id){

        //borrar de la tabla xform el id N x
        //armando la query
        $query = 'DELETE FROM xform WHERE id = ' . $id;

        //Ejecutar la query
        $this->database->query($query);

        \Drupal::messenger()->addWarning($this->t('The item with ID @id has been deleted.', ['@id' => $id]));


        $url = Url::fromRoute('people.listado')->toString();
        return new RedirectResponse($url);
    }


}
