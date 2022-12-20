<?php

namespace App\DataFixtures;

use Faker\Generator;
use App\Entity\Ingredients;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; $i++) {
            $Ingredients = new Ingredients();
            $Ingredients->setName($this->faker->word())
                ->setQuantity(mt_rand(1, 1000));

                $manager->persist($Ingredients);
        }


        $manager->flush();
    }
}
