<?php

namespace App\EventListener;

use App\Entity\Album;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', lazy: true, entity: Album::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', lazy: true, entity: Album::class)]
class AlbumListener
{
    public function postPersist(Album $album, PostPersistEventArgs $event): void
    {
        // ... do something to notify the changes
    }

    public function postUpdate(Album $album, PostUpdateEventArgs $event): void
    {
        // ... do something to notify the changes
    }
}
