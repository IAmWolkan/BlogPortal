<?php

declare(strict_types=1);

namespace BlogPortal\Api\EntityManager;

use function DeepCopy\deep_copy;

class EntityProperty {
  private $name;
  private $dbColumn;
  private $entityValue;
  private $storedEntityValue;
  private $options = [];

  public function __construct(string $name, string $type) {
    $this->name = $name;
    $this->entityValue = new EntityValue($type);
  }

  /**
   * Sets the name of the property that will be used in the application
   *
   * @return string
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * Sets the name of the column in the database
   *
   * @param string $dbColumn
   * @return self
   */
  public function setDbColumn(string $dbColumn): self {
    $this->dbColumn = $dbColumn;
    return $this;
  }

  /**
   * Returns the name of the column that is used in the database
   *
   * @return string
   */
  public function getDbColumn(): string {
    return $this->dbColumn;
  }

  /**
   * Setups different options that can be used for each property
   *
   * @param array $options
   * @return self
   */
  public function setOptions(array $options = []): self {
    $this->options = $options;
    return $this;
  }

  /**
   * Stores the value in the property
   *
   * @param mixed $value
   * @return void
   */
  public function setValue($value): void {
    $this->entityValue->setValue($value);
  }

  /**
   * Returns the stored value from the property.
   *
   * @return void
   */
  public function getValue() {
    if($this->entityValue !== null)
      return $this->entityValue->getValue();

    return $this->storedEntityValue->getValue() ?? null;
  }

  /**
   * Checks if the value has been changed from what is stored in DB.
   *
   * @return bool
   */
  public function hasChanged(): bool {
    $storedValue = null;
    if($this->storedEntityValue !== null)
      $storedValue = $this->storedEntityValue->getValue();

    $localValue = null;
    if($this->entityValue !== null)
      $localValue = $this->entityValue->getValue();

    return $storedValue !== $localValue;
  }

  /**
   * Undocumented function
   *
   * @return void
   */
  public function store(): void {
    $this->storedEntityValue = deep_copy($this->entityValue);
    $this->entityValue = null;
  }
}
