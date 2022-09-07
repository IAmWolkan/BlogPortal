<?php

declare(strict_types=1);

namespace BlogPortal\Api\EntityManager;

use BlogPortal\Api\Exceptions\EntityMissingPropertyException;

class EntityDefinition {
  private $database;
  private $table;
  private $primaryKey;
  private $properties;
  private $entityClass;

  public function setEntityClass(string $entityClass) {
    $this->entityClass = $entityClass;
  }

  /**
   * Sets the database that the entity table exists in
   *
   * @param string $database
   * @return self
   */
  public function database(string $database): self {
    $this->database = $database;
    return $this;
  }

  /**
   * Returns the database the entity table exists in
   *
   * @return string
   */
  public function getDatabase(): string {
    return $this->database;
  }

  /**
   * Sets the table the entity rows are stored in
   *
   * @param string $table
   * @return self
   */
  public function table(string $table): self {
    $this->table = $table;
    return $this;
  }

  /**
   * Returnes the table the entity rows are stored in
   *
   * @return string
   */
  public function getTable(): string {
    return $this->table;
  }

  /**
   * Sets property that is defined for the entity
   *
   * @param string $name The name of the property that will be used in the application
   * @param string $dbColumn The name of the column the property will map data towards
   * @param string $type What type the data has, ex. \DateTime, string, int or bool.
   * @param array $options Specific attribute data that is set in database, eg.
   * - autoIncrement: The property will not be set due to it being auto generated in the DB.
   * - allowsNull: The property allows null as value
   * - hasDefault: The property has a default value and does not require to be set
   * @return self
   */
  public function property(string $name, string $dbColumn, string $type, array $options = []): self {
    $property = new EntityProperty($name, $type);
    $property
      ->setDbColumn($dbColumn)
      ->setOptions($options);

    $this->properties[] = $property;
    return $this;
  }

  /**
   * Returns an array of properties
   *
   * @return EntityProperty[]
   */
  public function getProperties(): array {
    return $this->properties;
  }

  /**
   * Checks if property exists by name
   *
   * @param string $propertyName
   * @return boolean
   */
  public function hasProperty(string $propertyName): bool {
    foreach($this->getProperties() as $property) {
      if(strcasecmp($property->getName(), $propertyName) === 0)
        return true;
    }

    return false;
  }

  /**
   * Returns property based by property name
   *
   * @param string $propertyName
   * @return EntityProperty
   */
  public function getProperty(string $propertyName): EntityProperty {
    foreach($this->getProperties() as $property) {
      if(strcasecmp($property->getName(), $propertyName) === 0)
        return $property;
    }

    throw new EntityMissingPropertyException("Could not find property $propertyName on model {$this->entityClass}");
  }

  /**
   * Sets the primary key of the entity
   *
   * @param string $primaryKey
   * @return self
   */
  public function primaryKey(string $primaryKey): self {
    $this->primaryKey = $primaryKey;
    return $this;
  }

  /**
   * Retrieves the primary key of the entity
   *
   * @return string
   */
  public function getPrimaryKey(): string {
    return $this->primaryKey;
  }
}
