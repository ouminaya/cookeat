<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Recipe;
use App\Entity\Ingredients;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
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
        //Recipes
        
        for ($j = 0; $j < 25; $j ++) {
            $recipe =  new Recipe();
 
            $recipe->setName($this->faker->word())
                   ->setTime($this->faker->numberBetween(1, 1000))
                   ->setNbPeople($this->faker->numberBetween(1, 50))
                   ->setDifficulty($this->faker->numberBetween(1,5))
                   ->setDescription($this->faker->text(300))
                   ->setIsFavorite($this->faker->boolean());


            //Ingredients

            for ($k = 0; $k < mt_rand(5, 15); $k++) { 
                $ingredients = new Ingredients();
                $ingredients
                    ->setName($this->faker->word())
                    ->setQuantity(mt_rand(1, 1000));
                $manager->persist($ingredients);
                $recipe->addIngredient($ingredients);
            }

            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
