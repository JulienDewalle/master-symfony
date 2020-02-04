<?php

namespace App\Form\DataTransformer;


use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;

class TagsArrayToStringTransformer implements DataTransformerInterface
{
     /**
     * @var TagRepository
     */
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * On passe de Tag[] à une chaine
     */
    
    public function transform($tags): string
    {
        return implode(',', $tags);
    }

    /**
     * On passe d'une chaine à Tag[]
     */

    public function reverseTransform($string): array
    {
        //array_map trim sur chaque valeur
        //array_unique dédoublonne le tableau
        //array_filter supprime les chaines vides
        $names = array_filter(array_unique(array_map('trim', explode(',', $string))));

        //Je récupére kes tags déja existants en bdd
        $tags = $this->tagRepository->findBy(['name' => $names]);
        
        //on crée uniquement les tags non présents en bdd
        $newNames = array_diff($names, $tags);

        // pour chaque chaine saisie dans le formulaire je crée un tag
        foreach($newNames as $name){
            $tag = new Tag();
            $tag->setName($name);
            $tags[] = $tag;
        }
        return $tags;
    }


}