<?php

declare(strict_types=1);

namespace Drupal\recruitment_article\Mapper;

use Drupal\node\Entity\Node;
use Drupal\recruitment_article\Entity\Article;

class ArticlesMapper
{
  /**
   * @param Node[] $articles
   * @return array
   */
  public function map(array $articles): array
  {
    return array_map(function(Article $article) {
      return [
        'nid' => $article->id(),
        'bundle' => $article->bundle(),
        'path' => '/node/' . $article->id(),
        'title' => $article->getTitle(),
        'description' => $article->getDescription(),
        'image' => $article->getImage(),
      ];
    }, $articles);
  }

}
