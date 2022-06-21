<?php

declare(strict_types=1);

namespace Drupal\recruitment_article\Repository;

use Drupal\Core\Entity\EntityTypeManagerInterface;

class ArticleRepository
{
  private EntityTypeManagerInterface $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager)
  {
    $this->entityTypeManager = $entityTypeManager;
  }

  public function getAll(): array
  {
    return $this->entityTypeManager
      ->getStorage('node')
      ->loadByProperties([
        'type' => 'article',
      ]);
  }

}
