<?php

declare(strict_types=1);

namespace BlogPortal\Api\EntityManager;

class Entity {
  /** @var EntityDefinition[] */
  private static $definitions = [];

  /**
   * Sets up definitions of each entity
   *
   * @param string $entityClass
   * @return EntityDefinition
   */
  public static function define(string $entityClass): EntityDefinition {
    $definition = new EntityDefinition();
    $definition->setEntityClass($entityClass);
    
    self::$definitions[$entityClass] = $definition;
    return $definition;
  }

  /**
   * Retrives the definition of the current entity
   *
   * @return EntityDefinition
   */
  public function getDefinition(): EntityDefinition {
    return self::$definitions[get_class($this)] ?? null;
  }

  /**
   * Magic method that generates geters and seters for each property
   *
   * @param string $name
   * @param mixed $value
   * @return void
   */
  public function __call(string $name, $value) {
    if(str_starts_with($name, 'set')) {
      $property = $this->getProperty($name);
      $property->setValue($value);
      return;
    }

    if(str_starts_with($name, 'get')) {
      $property = $this->getProperty($name);
      return $property->getValue();
    }
  }

  /**
   * Fetches property by name from entity defintion
   *
   * @param string $propertyName
   * @return EntityProperty
   */
  private function getProperty(string $propertyName): EntityProperty {
    $propertyName = strtolower($propertyName);
    $propertyName = substr($propertyName, 3);

    $definition = self::$definitions[get_class($this)];
    return $definition->getProperty($propertyName);
  }
}
