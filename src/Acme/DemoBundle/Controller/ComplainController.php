<?php

namespace Acme\DemoBundle\Controller;

use Acme\DemoBundle\Entity\Complain;
use Acme\DemoBundle\Form\ComplainType;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Acme\DemoBundle\Form\ContactType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ComplainController extends Controller
{

#list 

    /**
     * @Route("/", name="_demo")
     * @Template()
     */
    public function indexAction()
    {
	$complains = $this->getDoctrine()
	              ->getRepository('AcmeDemoBundle:Complain')
	              ->findAll();

      if (!$complains) {
        throw $this->createNotFoundException('No complains found');
      }
    
      $build['complains'] = $complains;
      return $this->render('AcmeDemoBundle:Complain:complain_show_all.html.twig', $build);
    }

/////////////////////////////////////////////////////////////CRUD Operations of Book////////////////////////////////////////////

#add
	public function addAction()
		{
			$complain = new Complain();
			$form = $this->createForm(new ComplainType(), $complain);
                        
                        $validator = $this->get('validator');
                        $errors = $validator->validate($complain);
		
			$request = $this->get('request');
			$form->handleRequest($request);
		
			if($request->getMethod() == 'POST')
			{

				if($form->isValid())
				{
					$em = $this->getDoctrine()->getManager();
					$complain->setDate(new \DateTime("now"));
					$em->persist($complain);
					$em->flush();
                                        
                                       return $this->render('AcmeDemoBundle:Complain:thanks.html.twig');
					
				}
			
			   return $this->render('AcmeDemoBundle:Complain:add.html.twig',array('form'=>$form->createView())); 

			}

			   return $this->render('AcmeDemoBundle:Complain:add.html.twig',array('form'=>$form->createView())); 

		}
#show	
       public function showAction($id) {
	      $complain = $this->getDoctrine()
			   ->getRepository('AcmeDemoBundle:Complain')
			   ->find($id);
	      if (!$complain) {
		      throw $this->createNotFoundException('No complain found by id ' . $id);
	      }
	    
	      $build['complain'] = $complain;
	      return $this->render('AcmeDemoBundle:Complain:complain_show.html.twig', $build);
	    }
 
#delete
		 
		 public function deleteAction($id, Request $request) {

		    $em = $this->getDoctrine()->getManager();
		    $complain = $em->getRepository('AcmeDemoBundle:Complain')->find($id);
		    if (!$complain) {
		      throw $this->createNotFoundException(
			      'No complain found for id ' . $id
		      );
		    }

		      $em->remove($complain);
		      $em->flush();
		      	return $this->redirect($this->generateUrl('acme_complain_home'));
		      return new Response('تم مسح بنجاح');

		    
		    $build['form'] = $form->createView();
		    return $this->render('AcmeDemoBundle:Complain:news_add.html.twig', $build);
		}

	}
