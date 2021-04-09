<?php

namespace Drupal\event_register\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Term;

/**
 * Implements Registration form.
 */
class DepartmentForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'department_form';
  }

  /**
   * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['machine_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Department Machine Name'),
      '#required' => TRUE,
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Department Name'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit')
    ];
    return $form;
  }
  
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $categories_vocabulary = 'department'; // Vocabulary machine name
    $term = Term::create(array(
      'parent' => array(),
      'name' => $form_state->getValue('name'),
      'machine_name' =>  $form_state->getValue('machine_name'),
      'vid' => $categories_vocabulary,
    ))->save();


    \Drupal::messenger()->addStatus('Successfully saved');
  }
  
}
