<?php

declare(strict_types=1);

namespace Drupal\recruitment_article\Plugin\rest\resource;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\recruitment_article\Mapper\ArticlesMapper;
use Drupal\recruitment_article\Repository\ArticleRepository;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an Article Resource
 *
 * @RestResource(
 *   id = "article_resource",
 *   label = @Translation("Article Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/article_resource"
 *   }
 * )
 */
class ArticleResource extends ResourceBase
{
  protected ArticleRepository $articleRepository;

  protected ArticlesMapper $articlesMapper;

  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ): ResourceBase {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get(ArticleRepository::class),
      $container->get(ArticlesMapper::class)
    );
  }

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    ArticleRepository $articleRepository,
    ArticlesMapper $articlesMapper
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->articleRepository = $articleRepository;
    $this->articlesMapper = $articlesMapper;
  }

  /**
   * Responds to entity GET requests.
   * @return ResourceResponse
   */
  public function get(): ResourceResponse
  {
    // gets all articles from the database
    $articles = $this->articleRepository->getAll();
    // maps all articles into an array
    $mappedArticles = $this->articlesMapper->map($articles);
    // packs the articles into the response
    $response = new ResourceResponse($mappedArticles);
    // adds cache rule - cache will be invalidated when any of articles will be created/updated/deleted
    $response->addCacheableDependency(CacheableMetadata::createFromRenderArray([
      '#cache' => [
        'tags' => [
          'node_list:article',
        ],
      ],
    ]));
    return $response;
  }

}
