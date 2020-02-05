<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Product;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\MakerBundle\Maker\MakeEntity;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Migrations\Version\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $passwordEncoder){

        $this->slugger = $slugger;
        $this->passwordEncoder = $passwordEncoder;

    }


    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        // Créer les tags
        for($i=1;$i<=10;$i++){
            $tag = new Tag();
            $tag->setName('Tag '.$i);
            $manager->persist($tag);
        } 

        //on crée les utilisateurs
        $users = []; // Le tableau va nous aider à stocker les instances des users
        for ($i=0; $i <= 10; $i++) {
            $email = (1 === $i) ? 'julien.dewalle@hotmail.fr' : $faker->email;
            $roles = (1 === $i) ? ['ROLE_ADMIN'] : ['ROLE_USER'];

            $user = new User();
            $user->setUsername($email);
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, 'test')
            );
            $user->setRoles($roles);
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
