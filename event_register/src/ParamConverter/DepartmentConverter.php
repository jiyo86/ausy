<?php

namespace Drupal\event_register\ParamConverter;

use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\Routing\Route;
use Drupal\Core\ParamConverter\ParamConverterInterface;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides upcasting for a node entity in preview.
 */
class DepartmentConverter implements ParamConverterInterface {

  /**
   * {@inheritdoc}
   */
  public function convert($value, $definition, $name, array $defaults) {
    $query = \Drupal::entityQuery('taxonomy_term');
    $query->condition('vid', 'department');
    $query->condition('machine_name', $value);
    $terms = $query->execute();
    $tid = array_shift($terms);
    return Term::load($tid);
  }

  /**
   * {@inheritdoc}
   */
  public function applies($definition, $name, Route $route) {
    return (!empty($definition['type']) && $definition['type'] == 'department');
  }

}
