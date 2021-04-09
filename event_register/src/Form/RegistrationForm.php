<?php

namespace Drupal\event_register\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

/**
 * Implements Registration form.
 */
class RegistrationForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'registration_form';
  }

  /**
   * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state, $department = NULL) {
    
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Employee Name'),
      '#required' => TRUE,
    ];
    $form['spouse'] = [
      '#type' => 'select',
      '#title' => $this->t('Spouse?'),
      '#options' => [
        0 => $this->t('No'),
        1 => $this->t('Yes'),
      ],
      '#required' => TRUE,
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];

    $form['children'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of children'),
      '#required' => TRUE,
    ];
    $form['department'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Department'),
      '#required' => TRUE,
      '#default_value' => $department->get('name')->value,
      '#attributes' => [
        'disabled' => 'disabled'
      ]
    ];
    $form['vegetarians'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of vegetarians'),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Register')
    ];
    return $form;
  }
  
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValues()['email'];
    if (!\Drupal::service('email.validator')->isValid($email)) {
      $form_state->setErrorByName('Email', $this->t('Email address is not a valid one.'));
    }

    $query = \Drupal::entityQuery('node')
      ->condition('type', 'registration')
      ->condition('field_email', $email);
    $results = $query->execute();
    if ($results) {
      $form_state->setErrorByName('Email', $this->t('Email address already registered.'));
    }

    $spouse = $form_state->getValue('spouse');
    $kids = $form_state->getValue('children');
    $total = $spouse + $kids + 1;
    if ($form_state->getValue('vegetarians') > $total) {
      $form_state->setErrorByName('vegetarians', $this->t('Amount of vegetarians can not be higher than the total amount of people'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $registration = Node::create(['type' => 'registration']);
    $registration->set('title', $form_state->getValue('name'));
     $registration->set('status', 1);
    $registration->set('field_email', $form_state->getValue('email'));
    $registration->set('field_one_plus', $form_state->getValue('spouse'));
    $registration->set('field_kids', $form_state->getValue('children'));
    $registration->set('field_department', $form_state->getValue('department'));
    $registration->set('field_vegetarians', $form_state->getValue('vegetarians'));
    $registration->enforceIsNew();
    $registration->save();

    \Drupal::messenger()->addStatus('Successfully Registered');
  }
}
