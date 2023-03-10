<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Recipe;
use App\Entity\Category;
use App\Entity\Ingredients;
use App\Service\DataFetcherService;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private DataFetcherService $fetcher,
    ) {
        $this->faker = Factory::create('fr_FR');
    }


    public function load(ObjectManager $manager){

        $users= [];
        //User
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setFirstname($this->faker->firstName())
                ->setLastname($this->faker->lastName())
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER']);

            
            $hashPassword = $this->hasher->hashPassword(
                $user,
                'password'
            );
            
            $user->setPassword($hashPassword);
            $manager->persist($user);
            $users[] = $user;
        }
        //categorie
        $recipes = $this->fetcher->fetchData();
        $categories = [];
        $categoriesNames = ['entrée', 'plat', 'dessert'];

        foreach ($categoriesNames as $name) {
            $category = new Category;
            $category->setTitle($name);
            $manager->persist($category);
            $categories[] = $category;
        }
        //Recipes
        foreach ($recipes as $recipeImported) {

            
            
            $recipeImported = $recipeImported['recipes'][0];
            
            
            $recipe =  new Recipe();
            $recipe->setName($recipeImported['title'])
                ->setTime($recipeImported['cookingMinutes'])
                ->setNbPeople($this->faker->numberBetween(1, 50))
                ->setDifficulty($this->faker->numberBetween(1, 5))
                ->setDescription($recipeImported['instructions'])
                ->setIsFavorite($this->faker->boolean())
                ->setIsPublic($this->faker->boolean())
                ->setCategory($categories[$this->faker->numberBetween(0, 2)])
                ->setUser($users[$this->faker->numberBetween(0, count($users) - 1)]);


                $manager->persist($recipe);


            //Ingredients
            
            foreach ($recipeImported['extendedIngredients'] as $ingredient) {

                $ingredients = new Ingredients();
                $ingredients
                    //->setid($ingredient('id'))
                    ->setName($ingredient['name'])
                    ->setQuantity($ingredient['amount']);
                    
               
                    $manager->persist($ingredients);
                $recipe->addIngredient($ingredients);
            }

            
        }

    $manager->flush();

       
     
}

}