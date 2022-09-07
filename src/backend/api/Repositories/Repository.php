<?php

declare(strict_types=1);

namespace BlogPortal\Api\Repositories;

use BlogPortal\Api\Database\Sql;
use BlogPortal\Api\EntityManager\{Entity, EntitySqlBuilder};
use BlogPortal\Api\Exceptions\EntityMissingPropertyException;

class Repository {
  private $sql;

  /** @var Entity */
  private $entity;
  private $entitySqlBuilder;

  public function __construct(Sql $sql, EntitySqlBuilder $entitySqlBuilder) {
    $this->sql = $sql;
    $this->entitySqlBuilder = $entitySqlBuilder;
  }

  /**
   * Sets the entity that we will be working with
   *
   * @param string $entityClass
   * @return void
   */
  public function setEntity(string $entityClass): void {
    $this->entity = new $entityClass;
  }

  /**
   * Finds entity by primary key
   *
   * @param integer $primaryKey
   * @return Entity|null
   */
  public function find(int $primaryKey): ?Entity {
    $definition = $this->entity->getDefinition();

    // When using find we only need one filter
    $filter = [
      $definition->getPrimaryKey() => $primaryKey
    ];

    $entity = $this->search($filter);

    // If no entites found then we return null
    if(empty($entity))
      return null;

    /**
     * Returns the latest version of an entity if we have multiple
     * this could happend if we are storing removed data for analytics or
     * legal reasons.
     */ 
    $foundData = end($entity);
    return $this->mapEntityData($foundData);
  }

  /**
   * Searches for wanted enties based on filters that has been set
   *
   * @param array $filters
   * @return array
   */
  public function search(array $filters): array {
    $definition = $this->entity->getDefinition();

    // Loops trough each filter to make sure each exists as a property
    foreach($filters as $filterKey => $filterValue) {
      if(!$definition->hasProperty($filterKey)) {
        $msg = "$filterKey is not a properity on model ".get_class($this->entity);
        throw new EntityMissingPropertyException($msg);
      }
    }

    // Generates the requires select query string
    $sqlRaw = $this->entitySqlBuilder
      ->setEntity($this->entity)
      ->addFilters($filters)
      ->getSelectQuery();

    // Returns found records
    return $this->sql->fetchAll($sqlRaw);
  }

  /**
   * Retrives all records of a specific entity
   *
   * @return array
   */
  public function getAll(): array {
    $sqlRaw = $this->entitySqlBuilder
      ->setEntity($this->entity)
      ->getSelectQuery();

    $results = $this->sql->fetchAll($sqlRaw);
    return $results;
  }

  /**
   * Saved the new entity as a record in the database
   *
   * @param Entity $entity
   * @return boolean
   */
  public function save(Entity $entity): bool {
    return true;
  }

  /**
   * Deletes an existing record from the database
   *
   * @param Entity $entity
   * @return boolean
   */
  public function delete(Entity $entity): bool {
    return true;
  }

  /**
   * Maps record data to entity model
   *
   * @param array $data
   * @return void
   */
  private function mapEntityData(array $data) {
    $entity = new (get_class($this->entity));

    foreach($data as $column => $value) {
      $setMethod = 'set'.ucfirst($column);
      $entity->$setMethod($value);
    }

    return $entity;
  }
}
