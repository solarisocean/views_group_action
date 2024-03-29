<?php

namespace Drupal\views_group_action;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Manages discovery and instantiation of Views group action plugins.
 *
 * @see \Drupal\views_group_action\Annotation\ViewGroupActionForm
 * @see plugin_api
 */
class ViewsGroupActionFormPluginManager extends DefaultPluginManager {

  /**
   * Default values for each Views group action form type plugin.
   *
   * @var array
   */
  protected $defaults = [
    'id' => '',
    'label' => '',
  ];

  /**
   * Constructs a new ViewGroupActionFormPluginManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/ViewGroupAction/Form',
      $namespaces,
      $module_handler,
      '\Drupal\views_group_action\Plugin\ViewGroupAction\Form\ActionFormInterface',
      '\Drupal\views_group_action\Annotation\ViewGroupActionForm'
    );

    $this->alterInfo('views_group_action_form_info');
    $this->setCacheBackend($cache_backend, 'views_group_action_form_plugins');
  }

  /**
   * {@inheritdoc}
   */
  public function processDefinition(&$definition, $plugin_id) {
    parent::processDefinition($definition, $plugin_id);

    foreach (['id', 'label'] as $required_property) {
      if (empty($definition[$required_property])) {
        throw new PluginException(sprintf('The Views group action form type %s must define the %s property.', $plugin_id, $required_property));
      }
    }
  }

}
