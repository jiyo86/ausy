<?php

namespace Drupal\event_register\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a block with a total Registartion count.
 *
 * @Block(
 *   id = "registration_count_block",
 *   admin_label = @Translation("Registration Counts"),
 * )
 */
class RegistrationBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
     return array(
      '#type' => 'markup',
      '#markup' => $this->getRegContent(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();
  
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['registartion_count_settings'] = $form_state->getValue('registartion_count_settings');
  }

  function getRegContent() {
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'registration');
    $result = $query->count()->execute();
    return $this->t('Registration Counts @count', ['@count' => $result]);
  }
}
