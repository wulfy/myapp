<?php
namespace MyApp\FilmothequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\StoreBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use MyApp\FilmothequeBundle\Entity\Categorie;

class DefaultController extends Controller
{
    public function indexAction()
    {
		$message = 'Mon premier message';
        return $this->render('MyAppFilmothequeBundle:Default:index.html.twig', array('message' => $message));
    }
	
	public function listAction()
	{
		$em = $this->getDoctrine()->getManager();
		
		$categories_repository = $this->getDoctrine()->getRepository('MyAppFilmothequeBundle:Categorie');
		$categories = $categories_repository->findAll();
		
		return $this->container->get('templating')->renderResponse('MyAppFilmothequeBundle:Default:list.html.twig',array(
			'categories' => $categories));
	}
	
	public function initAction()
	{
		$em = $this->getDoctrine()->getManager();
		
		$categories_repository = $this->getDoctrine()->getRepository('MyAppFilmothequeBundle:Categorie');
		$categories = $categories_repository->findAll();
		
		if(sizeof($categories) > 0)
		{
			$message = "Catégories déjà existantes";
		}else
		{
			$categorie1 = new Categorie();
			$categorie1->setNom('Comédie');
			$em->persist($categorie1);

			$categorie2 = new Categorie();
			$categorie2->setNom('Science-fiction');
			$em->persist($categorie2);

			$em->flush();

			$message = "Catégories créées avec succès";
		}
			return new Response($message);
	}
}
