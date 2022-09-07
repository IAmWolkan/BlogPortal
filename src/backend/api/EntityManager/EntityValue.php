<?php

declare(strict_types=1);

namespace BlogPortal\Api\EntityManager;

use BlogPortal\Api\Exceptions\EntityValueMissingException;

class EntityValue {
  private $value;
  private $type;
  
  public function __construct(string $type) {
    $this->type = $type;
  }

  public function setValue($value) {
    $this->value = $value;
  }

  public function getValue() {
    switch($this->type) {
      case 'string':
        return strval($this->value);
        break;
      case 'int':
        return intval($this->value);
        break;
      default:
        throw new EntityValueMissingException("{$this->type} is not a valid value type");
    }
  }

  public function toSql() {
    switch($this->type) {
      case 'string':
        return strval($this->value);
        break;
      case 'int':
        return intval($this->value);
        break;
      default:
        throw new EntityValueMissingException("{$this->type} is not a valid value type");
    }
  }
}
