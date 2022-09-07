<?php

declare(strict_types=1);

namespace BlogPortal\Api\Models;

use BlogPortal\Api\EntityManager\Entity;

Entity::define(Post::class)
  ->database('blogportal')
  ->table('posts')
  ->property('id', 'id', 'int', ['autoIncrement' => true])
  ->primaryKey('id');

class Post extends Entity {}
