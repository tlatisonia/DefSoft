<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Entity\Products;
use App\Form\ProductsType;
use Psr\Log\LoggerInterface;
use App\Event\AddProductEvent;
use App\Event\AllProductEvent;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/products')]
class ProductsController extends AbstractController
{
    public function __construct(
        private LoggerInterface $logger,
        private EventDispatcherInterface $dispatcher
    )
    {}

    #[Route('/', name: 'app_products_index', methods: ['GET'])]
    public function index(ProductsRepository $productsRepository): Response
    {
       $products =$productsRepository->findAll();
        $allProductsEvent = new AllProductEvent($products);
        $this->dispatcher->dispatch($allProductsEvent, AllProductEvent::ALL_PRODUCTS_EVENT);
        return $this->render('products/index.html.twig', [
            'products' => $productsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_products_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           // dd($form['quantity']->getData());
            $stock = new Stock();
            $stock->setQuantite($form['quantity']->getData());
            $stock->addProduct($product);
            $entityManager->persist($stock);
            $entityManager->persist($product);
            $entityManager->flush();
            $addProductsEvent = new AddProductEvent($product);
            $this->dispatcher->dispatch($addProductsEvent, AddProductEvent::ADD_PRODUCTS_EVENT);
            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('products/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_products_show', methods: ['GET'])]
    public function show(Products $product): Response
    {
        return $this->render('products/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_products_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Products $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
           if($product->getStock()== null){
            $stock = new Stock();
            $stock->setQuantite($form['quantity']->getData());
            $stock->addProduct($product);
           }else{
            $stock = $product->getStock();
            $stock->setQuantite($form['quantity']->getData());
           }
            
            $entityManager->persist($stock);
            $entityManager->flush();

            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('products/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_products_delete', methods: ['POST'])]
    public function delete(Request $request, Products $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
    }
}
