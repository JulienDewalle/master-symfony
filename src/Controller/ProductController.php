<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\Uploader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/admin/product/create", name="product_create")
     */
    public function create(Request $request, SluggerInterface $slugger, Uploader $uploader)
    {
        $product = new Product();
        // On crée un formulaire avec deux paramètres: la classe du formulaire et l'objet à ajouter dans la BDD
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        

            if ($form->isSubmitted() && $form->isValid()) {

                    // On fait l'upload ...
                if ($image = $form->get('image')->getData()) {
                    $fileName = $uploader->upload($image);
                    $product->setImage($fileName);
                }
                
                // Je génére le slug à la creation du produit
                $product->setSlug($slugger->slug($product->getName())->lower());

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
     * @Route ("/admin/product/remove/{id}", name="product_remove", methods={"POST"})
     */
    public function remove(Request $request, Product $product, EntityManagerInterface $entityManager, Uploader $uploader)
    {

        //on vérifie la validité du token CSRF  On se protege d'une faille CSRF
        if ($this->isCsrfTokenValid('remove', $request->get('token'))){
            if($product->getImage()){
                $uploader->remove($product->getImage());
            }

            $entityManager->remove($product);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Votre produit est supprimé');

        return $this->redirectToRoute('product_all');

    }

    /**
     * @Route("/product/{slug}", name="product_show")
     */
    public function show($slug/*Product $product permet de remplacer toute la selection du dessous */) {

        // On récupere le dépôt qui contient nos produits
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        // SELECT * FROM product WHERE slug = $slug
        //equivalent :  $product = $productRepository->findOneBySlug($slug);
        $product = $productRepository->findOneBy(['slug' => $slug]);

        //Si le produit n'existe pas
        if(!$product) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route ("/product/edit/{id}", name="product_edit")
     */

    public function edit(Product $product, Request $request, EntityManagerInterface $productRepository, Uploader $uploader)
    {
        $this->denyAccessUnlessGranted('edit', $product);

        //on crée formulaire avec le produit à modifier
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // On fait l'upload ...
            if ($image = $form->get('image')->getData()) {

                //supprimer l'image
                if($product->getImage()){
                    $uploader->remove($product->getImage());
                }


                $fileName = $uploader->upload($image);
                $product->setImage($fileName);
            }

            $productRepository->persist($product);
            $productRepository->flush();

            $this->addFlash('success', 'Votre produit est mofidié');

        }
        return $this->render('product/edit.html.twig', [
            'product' => $form->createView()
        ]);
    }

}