<?php

declare(strict_types=1);

namespace Drupal\people\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for People routes.
 */
final class FormCrudController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function __invoke($type, $id = null): array {

    switch ($type) {
      case 'new':
        $build['people_xform_crud'] = array(
          '#theme' => 'xform_crud',
          '#type' => $type
        );
        break;

      case 'edit':
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
        $build['people_xform_crud'] = array(
          '#theme' => 'xform_crud',
          '#form_data' => $form_data,
          '#type' => $type,
          '#id' => $id
        );
        break;
    }

    return $build;
  }

}
