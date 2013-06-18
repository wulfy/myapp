<?php
namespace MyApp\FilmothequeBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Acme\StoreBundle\Entity\Product;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MyApp\FilmothequeBundle\Entity\Acteur;
use MyApp\FilmothequeBundle\Form\ActeurForm;

class ActeurController extends ContainerAware
{
    public function listerAction()
    {
		$em = $this->container->get('doctrine')->getEntityManager();

		$acteurs= $em->getRepository('MyAppFilmothequeBundle:Acteur')->findAll();

		return $this->container->get('templating')->renderResponse('MyAppFilmothequeBundle:Acteur:lister.html.twig', 
		array(
		'acteurs' => $acteurs
		));
    }
    
    public function ajouterAction()
    {
		$acteur = new Acteur();
		$form = $this->container->get('form.factory')->create(new ActeurForm(), $acteur);
		$message= "";
		$request = $this->container->get('request');

		  if ($request->getMethod() == 'POST') 
		  {
			$form->bind($request);

			if ($form->isValid()) 
			{
			  $em = $this->container->get('doctrine')->getManager();
			  $em->persist($acteur);
			  $em->flush();
			  $message='Acteur ' .$acteur->getNom(). ' ajouté avec succès !';
			}
		  }

		  return $this->container->get('templating')->renderResponse(
		'MyAppFilmothequeBundle:Acteur:ajouter.html.twig',
		  array(
			'form' => $form->createView(),
			'message' => $message,
		  ));
    }
	
	
	public function editerAction($id = null)
	{
		$message='';
		$em = $this->container->get('doctrine')->getManager();
		$modify = false;
		
		if (isset($id)) 
		{
			$modify = true;
			// modification d'un acteur existant : on recherche ses données
			$acteur = $em->find('MyAppFilmothequeBundle:Acteur', $id);

			if (!$acteur)
			{
				$message='Aucun acteur trouvé';
			}
		}
		else 
		{
			// ajout d'un nouvel acteur
			$acteur = new Acteur();
		}

		$form = $this->container->get('form.factory')->create(new ActeurForm(), $acteur);

		$request = $this->container->get('request');

		if ($request->getMethod() == 'POST') 
		{
				$form->bindRequest($request);

		if ($form->isValid()) 
		{
			$em->persist($acteur);
			$em->flush();
			if (isset($id)) 
			{
				$message='Acteur modifié avec succès !';
			}
			else 
			{
				$message='Acteur ajouté avec succès !';
			}
		}
		}

		return $this->container->get('templating')->renderResponse(
	'MyAppFilmothequeBundle:Acteur:editer.html.twig',
		array(
		'form' => $form->createView(),
		'message' => $message,
		'modify' => $modify
		));
	}


    public function modifierAction($id)
    {
	return $this->container->get('templating')->renderResponse(
'MyAppFilmothequeBundle:Acteur:modifier.html.twig');
    }

    public function supprimerAction($id)
    {
	  $em = $this->container->get('doctrine')->getManager();
	  $acteur = $em->find('MyAppFilmothequeBundle:Acteur', $id);
			
	  if (!$acteur) 
	  {
		throw new NotFoundHttpException("Acteur non trouvé");
	  }
			
	  $em->remove($acteur);
	  $em->flush();        


	  return new RedirectResponse($this->container->get('router')->generate('myapp_acteur_lister'));
    }
}