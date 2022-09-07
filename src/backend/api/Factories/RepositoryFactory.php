<?php

declare(strict_types=1);

namespace BlogPortal\Api\Factories;

use Auryn\Injector;
use BlogPortal\Api\Repositories\Repository;

class RepositoryFactory {
  private $injector;

  public function __construct(Injector $injector) {
    $this->injector = $injector;
  }

  /**
   * Create function to dynamically create entity specific repositories
   *
   * @param string $entityClass
   * @return Repository
   */
  public function create(string $entityClass): Repository {
    $repository = $this->injector->make(Repository::class);
    $repository->setEntity($entityClass);

    return $repository;
  }
}
