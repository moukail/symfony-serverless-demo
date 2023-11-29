<?php

namespace App\EventListener;

use App\Entity\Artist;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postUpdate, lazy: true, entity: Artist::class)]
class ArtistPostUpdateListener
{
    public function __invoke(Artist $artist, PostUpdateEventArgs $event): void
    {

    }
}
