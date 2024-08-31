<?php

declare(strict_types=1);

namespace Drupal\form_crud\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns responses for Form Crud routes.
 */
final class FormCrudController extends ControllerBase
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
  public function __invoke(Request $request, $type, $id = null)
  {
    switch ($type) {
      case 'abm':
        $url = $this->crear_o_editar($request);
        return new RedirectResponse($url);
      case 'edit':
        $build = $this->ir_template_edicion($type, $id);
        break;
      case 'new':
        $build = $this->ir_template_new($type);
        break;
      case 'delete':
        $url = $this->borrar($id);
        return new RedirectResponse($url);
      default:
        $build = $this->listado();
    }

    return $build;
  }


  public function crear_o_editar($request)
  {
    //Cuando tengo que hacer un insert o update
    $form_id = $request->request->get('form_id');
    $form_type = $request->request->get('form_type');
    $nombre = $request->request->get('nombre');
    $description = $request->request->get('description');
    $email = $request->request->get('email');
    $vehicle1 = $request->request->get('vehicle1');
    $vehicle2 = $request->request->get('vehicle2');
    $vehicle3 = $request->request->get('vehicle3');
    $pwd = $request->request->get('pwd');

    if ($form_type == 'edit') {
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
    } elseif ($form_type == 'new') {
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
    }
    // Ejecutar la consulta.
    $this->database->query($query, $args);
    if (isset($form_id) & $form_id != "") {
      \Drupal::messenger()->addWarning($this->t('The item with ID @id has been modified.', ['@id' => $form_id]));
    } else {
      $id = $this->database->lastInsertId();
      \Drupal::messenger()->addWarning($this->t('The item with ID @id has been created.', ['@id' => $id]));
    }
    $url = Url::fromRoute('form_crud.crud')->toString();
    return $url;
  }

  public function ir_template_edicion($type, $id = null)
  {
    //cuando hay que hacer una UPDATE
    $form_data = [];
    if (isset($id)) {
      // Obtener datos de la base de datos según el ID
      $database = \Drupal::database();
      $query = $database->select('xform', 'x')
        ->fields('x')
        ->condition('id', $id)
        ->execute()
        ->fetchAssoc();
      $form_data = $query ? $query : [];
    }
    $build['form_crud_form_crud'] = array(
      '#theme' => 'xform_crud',
      '#form_data' => $form_data,
      '#type' => $type,
      '#id' => $id
    );
    return $build;
  }

  public function ir_template_new($type)
  {
    //Cuando queremos abrir el formulario para completar un nuevo registro
    $build['form_crud_form_crud'] = array(
      '#theme' => 'xform_crud',
      '#type' => $type
    );
    return $build;
  }

  public function borrar($id)
  {
    //borrar de la tabla xform el id N x
    //armando la query
    $query = 'DELETE FROM xform WHERE id = ' . $id;
    //Ejecutar la query
    $this->database->query($query);
    \Drupal::messenger()->addWarning($this->t('The item with ID @id has been deleted.', ['@id' => $id]));
    $url = Url::fromRoute('form_crud.crud')->toString(); //form-crud
    return $url;
  }

  public function listado(){
    //Creo el listado para mostrar en una tabla
    //creo la sentencia a ejecutar
    $query = "SELECT * FROM {xform}";
    //Ejecuto la sentencia a la base de datos
    $rows = $this->database->query($query);

    //recorro los resultados para ponerlos en un array
    $items = [];
    foreach ($rows as $row) {
      $items[] = (array) $row;
    }

    $header = [];
    if (!empty($items)) {
      $header = array_keys((array) $items[0]);
    }

    $build['crud_tabla_listado'] = array(
      '#theme' => 'tabla_listado',
      '#people_header' => $header,
      '#people_rows' => $items,
    );

    return $build;
  }
}
