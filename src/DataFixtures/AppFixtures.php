<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\MakerBundle\Maker\MakeEntity;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    /**
     * @var SluggerInterface
     */
    public function __contruct(SluggerInterface $slugger){

        $this->slugger = $slugger;

    }    

    public function load(ObjectManager $manager)
    {
        for ($i=0; $i <= 100; $i++) {
        $product = new Product();
        $product->setName('iPhone '.$i);
        $product->setSlug($this->Slugger->slug($product->getName())->lower());
        $product->setDescription('Un iPhone de '.rand(2000, 2020));
        $product->setPrice(rand(10, 100)*100);
        $manager->persist($product);
    }

        $manager->flush();

    }
}
