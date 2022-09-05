<?php

declare(strict_types=1);

namespace BlogPortal\Api\Database;

class SqlRaw {
  /** @var string */
  private string $query = '';

  /** @var array */
  private array $params = [];

  public function setQuery(string $query): self {
    $this->query = $query;
    return $this;
  }

  public function getQuery(): string {
    return trim($this->query);
  }

  public function setParams(array $params): self {
    $this->params = $params;
    return $this;
  }

  public function getParams(): array {
    return $this->params;
  }

  public static function create(string $query, array $params = []): SqlRaw {
    $obj = new SqlRaw();
    $obj
      ->setQuery($query)
      ->setParams($params);

    return $obj;
  }
}
