<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Product;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/example", name="example")
     */
    public function exampleAction(Request $request)
    {
        // echo '<pre>';
        // print_r($request->getSession());
        // die("FINISHED");

        // replace this example code with whatever you need
        return $this->render('default/example.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/create", name="create_product")
     */
    public function createAction()
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to your action: createAction(EntityManagerInterface $em)
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(19.99);
        $product->setDescription('Ergonomic and stylish!');

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    /**
     * @Route("/show/{productId}", name="show_product")
     */
    public function showAction($productId)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($productId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$productId
            );
        }

        echo '<pre>';
        die(print_r($product));

        // ... do something, like pass the $product object into a template
    }

    /**
     * @Route("/edit/{productId}", name="edit_product")
     */
    public function editAction($productId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($productId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$productId
            );
        }

        $product->setName('New Product Name');
        $product->setPrice(5.99);


        // actually executes the queries (i.e. the UPDATE query)
        $entityManager->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/queries/{productId}", name="queries_product")
     */
    public function queriesAction($productId)
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);

        // looks for a single product by its primary key (usually "id")
        $product = $repository->find($productId);

        // dynamic method names to find a single product based on a column value
        $product = $repository->findOneById($productId);
        $product = $repository->findOneByName('Keyboard');

        // dynamic method names to find all products based on a column value
        $product = $repository->findByName('Keyboard');

        // dynamic method names to find a group of products based on a column value
        $products = $repository->findByPrice(19.99);

        // finds *all* products
        $products = $repository->findAll();

        echo '<pre>';
        die(print_r($products));

        // looks for a single product matching the given name and price
        $product = $repository->findOneBy(
            array('name' => 'Keyboard', 'price' => 19.99)
        );

        // looks for multiple products matching the given name, ordered by price
        $products = $repository->findBy(
            array('name' => 'Keyboard'),
            array('price' => 'ASC')
        );
    }
}
