<?php

namespace Drupal\timezone\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\timezone\Time;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an timezone block.
 *
 * @Block(
 *   id = "time",
 *   admin_label = @Translation("Time"),
 *   category = @Translation("timezone")
 * )
 */
class TimeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The timezone.time service.
   *
   * @var \Drupal\timezone\Time
   */
  protected $time;

  /**
   * Constructs a new ExampleBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\timezone\Time $time
   *   The timezone.time service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, Time $time) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('timezone.time')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $config = $this->configFactory->get('timezone.settings');
    $country = $config->get('country');
    $city = $config->get('city');
    $timezone = $this->time->getTime();

    if (!empty($city) && !empty($country) && !empty($timezone)) {
      $build = [
        '#theme' => 'timezone',
        '#city' => $city,
        '#country' => $country,
        '#timezone' => $timezone,
        // Enabling `max-age` for 1 minute as the time must be updated without
        // cache rebuild.
        '#cache' => [
          'max-age' => 60,
        ],
      ];
    }

    return $build;
  }

}
