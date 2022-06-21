<?php

declare(strict_types=1);

namespace Drupal\recruitment_article\Entity;

use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;
use Drupal\node\Entity\Node;

class Article extends Node
{
  public function getDescription(): ?string
  {
    return $this->get('body')->value;
  }

  public function getImage(): ?string
  {
    $id = $this->get('field_image')->target_id;

    if (!$id) {
      return null;
    }
    $image = File::load($id);
    $cropImageStyle = ImageStyle::load('crop_1_2');
    return $cropImageStyle->buildUri($image->createFileUrl());
  }

}
