<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
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

    /**
     * @Route("product/all", name="product_all")
     */
    public function all(ProductRepository $ProductRepository){

        $products = $ProductRepository
            ->findAll();

        return $this->render('product/all.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show($id/*Product $product permet de remplacer toute la selection du dessous */) {
        dump($id);
        // On récupere le dépôt qui contient nos produits
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        // SELECT * FROM product WHERE id = $id
        $product = $productRepository->find($id);

        //Si le produit n'existe pas
        if(!$product) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}