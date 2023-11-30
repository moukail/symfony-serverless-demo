<?php


namespace App\Tests\Unit;

use App\Tests\Support\UnitTester;
use Codeception\Test\Unit;
use Faker\Generator;
use Symfony\Component\Validator\Validator\TraceableValidator;

class AlbumTest extends Unit
{
    protected UnitTester $tester;
    private Generator $faker;
    private TraceableValidator $validator;

    protected function _before(): void
    {
        $this->faker = \Faker\Factory::create('nl_NL');
        $this->validator = $this->tester->grabService('validator');
    }

    // tests
    public function testCanMake()
    {

    }
}
