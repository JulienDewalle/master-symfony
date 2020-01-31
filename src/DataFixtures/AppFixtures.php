<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\MakerBundle\Maker\MakeEntity;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Migrations\Version\Factory;

class AppFixtures extends Fixture
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

    public function __construct(SluggerInterface $slugger){

        $this->slugger = $slugger;

    }    


    public function load(ObjectManager $manager)
    {
        $faker= \Faker\Factory::create('fr_FR');

        //on crée les utilisateurs
        $users = []; // Le tableau va nous aider à stocker les instances des users
        for ($i=0; $i <= 10; $i++) {
            $user = new User();
            $user->setUsername($faker->email);
            $manager->persist($user); 
            $users[] = $user;       
        }

        //on crée les catégories
        $categories = [];
        for($i=0; $i<=5; $i++) {
            $categorie = new Categorie();
            $categorie->setName($faker->name);
            $categorie->setSlug($this->slugger->slug($categorie->getName())->lower());
            $manager->persist($categorie);
            $categories[$i] = $categorie;
        }


        //on crée les produits
        for ($i=0; $i <= 100; $i++) {
        $product = new Product();
        $product->setName('iPhone '.$i);
        $product->setSlug($this->slugger->slug($product->getName())->lower());
        $product->setDescription('Un iPhone de '.rand(2000, 2020));
        $product->setPrice(rand(10, 100)*100);
        $product->setUser($users[rand(0, 9)]);
        $product->setCategorie($categories[rand(0, 5)]);
        $manager->persist($product);
    }

        $manager->flush();

    }
}
