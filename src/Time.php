<?php

namespace Drupal\timezone;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Helper service to get the current time based on timezone selection.
 */
class Time {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a Time object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Get transformed time.
   *
   * @return array
   *   Returns the transformed time, as per the selected timezone.
   */
  public function getTime() {
    $dateTime = [];
    $selectedTimezone = $this->configFactory->get('timezone.settings')->get('timezone');
    if (!empty($selectedTimezone)) {
      $date = new \DateTime('now', new \DateTimeZone($selectedTimezone));
      $dateTime = [$date->format('jS M Y - g:i A'), $selectedTimezone];
    }
    return $dateTime;
  }

}
