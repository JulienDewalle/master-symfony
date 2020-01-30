<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/create", name="product_create")
     */
    public function create(Request $request)
    {
        $product = new Product();
        // On crée un formulaire avec deux paramètres: la classe du formulaire et l'objet à ajouter dans la BDD
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        

            if ($form->isSubmitted() && $form->isValid()) {
                // On peut aussi utiliser le typage :
                // create(EntityManagerInterface $entityManager)
                $entityManager = $this->getDoctrine()->getManager();
    
                // On demande à Doctrine de mettre l'objet en attente
                $entityManager->persist($product);
    
                // Exécute la(es) requête(s) (INSERT...)
                $entityManager->flush();
    
                // return $this->redirectToRoute('product_list');
            }
            return $this->render('product/create.html.twig', [
                
            'form' => $form->createView(),
        ]);
    }
}