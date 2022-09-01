<?php

namespace Drupal\timezone\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure timezone settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * Expected timezones.
   *
   * @var string[]
   */
  public static array $timezones = [
    'America/Chicago',
    'America/New_York',
    'Asia/Tokyo',
    'Asia/Dubai',
    'Asia/Kolkata',
    'Europe/Amsterdam',
    'Europe/Oslo',
    'Europe/London',
  ];

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'timezone_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['timezone.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('timezone.settings');
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $config->get('city'),
      '#required' => TRUE,
    ];
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#default_value' => $config->get('country'),
      '#required' => TRUE,
    ];
    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#required' => TRUE,
      '#options' => array_combine(self::$timezones, self::$timezones),
      '#empty_value' => '_none',
      '#empty_option' => $this->t('Options in the select list'),
      '#default_value' => $config->get('timezone'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!preg_match('/^([a-zA-Z]+)$/', $form_state->getValue('country')) && empty($form_state->getValue('country'))) {
      $form_state->setErrorByName('country', $this->t("Country's value is incorrect"));
    }
    if (!preg_match('/^([a-zA-Z]+)$/', $form_state->getValue('city')) && empty($form_state->getValue('city'))) {
      $form_state->setErrorByName('city', $this->t("City's value is incorrect"));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('timezone.settings')
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('timezone', $form_state->getValue('timezone'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
