<?php

namespace App\Tests\Support\Helper;

use App\Entity\Album;
use App\Entity\Artist;
use Codeception\Module;
use Doctrine\ORM\EntityManager;

class Factories extends Module
{
    public function _beforeSuite($settings = array())
    {
        $factory = $this->getModule('DataFactory');
        $faker = \Faker\Factory::create('nl_NL');

        /** @var EntityManager $entityManager */
        $entityManager = $this->getModule('Doctrine2')->_getEntityManager();

        $factory->_define(Artist::class, [
            'name'   => $faker->name,
        ]);

        $factory->_define(Album::class, [
            'artist' => 'entity|'.Artist::class,
            'title' => $faker->title,
            'description' => $faker->paragraph,
        ]);
    }
}
