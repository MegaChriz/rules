<?php

/**
 * @file
 * Contains \Drupal\rules\Plugin\Action\PathAliasDelete.
 */

namespace Drupal\rules\Plugin\Action;

use Drupal\Core\Path\AliasStorageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\rules\Engine\RulesActionBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Delete alias for a path' action.
 *
 * @Action(
 *   id = "rules_path_aliases_delete",
 *   label = @Translation("Delete alias for a path"),
 *   context = {
 *     "path" = @ContextDefinition("string",
 *       label = @Translation("Existing system path"),
 *       description = @Translation("Specifies the existing path you wish to delete the alias of, for example 'node/1'. Use a relative path and do not add a trailing slash.")
 *     )
 *   }
 * )
 *
 * @todo: Add access callback information from Drupal 7.
 * @todo: Add group information from Drupal 7.
 */
class PathAliasDelete extends RulesActionBase implements ContainerFactoryPluginInterface {

  /**
   * The alias storage service.
   *
   * @var \Drupal\Core\Path\AliasStorageInterface
   */
  protected $aliasStorage;

  /**
   * Constructs a PathAliasDelete object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Path\AliasStorageInterface $alias_storage
   *   The alias storage service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AliasStorageInterface $alias_storage) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->aliasStorage = $alias_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('path.alias_storage')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    return $this->t('Delete alias for a path');
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $path = $this->getContextValue('path');
    $this->aliasStorage->delete(['path' => $path]);
  }
}
