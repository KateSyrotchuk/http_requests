<?php

namespace Acme\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Acme\StoreBundle\Document\Product;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/mongodb", name="mongodb")
     */
    public function indexAction()
    {
        $products = $this->get('doctrine_mongodb')
            ->getRepository('AcmeStoreBundle:Product')
            ->findAll();

        if (!$products) {
            throw $this->createNotFoundException('No product found');
        }

        return $this->render('AcmeStoreBundle:Default:show_all.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * @Route("/mongodb/create", name="mongodb_create")
     */
    public function createAction()
    {
        $product = new Product();
        $product->setName('A Foo Bar');
        $product->setPrice('19.99');

        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($product);
        $dm->flush();

        return $this->redirectToRoute('mongodb');
    }

    /**
     * @Route("/mongodb/show/{id}", name="mongodb_show")
     */
    public function showAction($id)
    {
        $product = $this->get('doctrine_mongodb')
            ->getRepository('AcmeStoreBundle:Product')
            ->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        return $this->render('AcmeStoreBundle:Default:show.html.twig', array(
            'product' => $product,
        ));
    }

    public function updateAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $product = $dm->getRepository('AcmeStoreBundle:Product')->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        $product->setName('New product name!');
        $dm->flush();

        return $this->redirect($this->generateUrl('homepage'));
    }
}
