<?php

namespace App\DataFixtures;

use App\Entity\Ingredients;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; $i++) {
            $Ingredients = new Ingredients();
            $Ingredients->setName('Ingredient'.$i)
                ->setQuantity(mt_rand(1, 1000));

                $manager->persist($Ingredients);
        }


       



        $manager->flush();
    }
}
