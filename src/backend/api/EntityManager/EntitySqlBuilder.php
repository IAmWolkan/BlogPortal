<?php

declare(strict_types=1);

namespace BlogPortal\Api\EntityManager;

use BlogPortal\Api\Database\SqlRaw;

class EntitySqlBuilder {
  private $entity;
  private $filters = [];

  /**
   * Sets the entity that we will be generating the query string for
   *
   * @param Entity $entity
   * @return self
   */
  public function setEntity(Entity $entity): self {
    $this->entity = $entity;
    return $this;
  }

  /**
   * Adds filter to be used when generating querys
   *
   * @param array $filters
   * @return self
   */
  public function addFilters(array $filters): self {
    $this->filters = $filters;
    return $this;
  }

  /**
   * Generates a sql query string based on filters and entity data 
   *
   * @param array $selects
   * @return SqlRaw
   */
  public function getSelectQuery(array $selects = []): SqlRaw {
    $entityDefinition = $this->entity->getDefinition();

    $database = $entityDefinition->getDatabase();
    $table = $entityDefinition->getTable();

    $properties = $entityDefinition->getProperties();
    $dbColumns = array_reduce($properties, function(array $columns, EntityProperty $property)use($selects) {
      if(empty($selects)) {
        $columns[] = $property->getDbColumn();
        return $columns;
      }

      $searchArray = array_map('strtolower', $selects);
      if(in_array(strtolower($property->getDbColumn()), $searchArray)) {
        $columns[] = $property->getDbColumn();
      }

      return $columns;
    }, []);
    $dbColumns = implode(',', $dbColumns);

    $params = [];
    $query = "SELECT $dbColumns FROM `$database`.`$table`";
    if(!empty($this->filters)) {
      $wheres = [];

      foreach($this->filters as $filterKey => $filterValue) {
        $wheres[$filterKey] = "$filterKey = :$filterKey";
        $params[":$filterKey"] = $filterValue;
      }

      $query .= " WHERE " . implode('AND', $wheres);
    }

    $sqlRaw = new SqlRaw();
    $sqlRaw->setQuery($query);
    $sqlRaw->setParams($params);

    return $sqlRaw;
  }
}
