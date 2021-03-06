<?php

namespace Acme\DemoBundle\Controller;

use Acme\DemoBundle\Entity\Advertising;
use Acme\DemoBundle\Form\AdvertisingType;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Acme\DemoBundle\Form\ContactType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdvertisingController extends Controller
{

#list 

    /**
     * @Route("/", name="_demo")
     * @Template()
     */
    public function indexAction()
    {
	$advs = $this->getDoctrine()
	              ->getRepository('AcmeDemoBundle:Advertising')
	              ->findAll();

      if (!$advs) {
        throw $this->createNotFoundException('No advertisings found');
      }
    
      $build['advs'] = $advs;
      return $this->render('AcmeDemoBundle:Advertising:advertising_show_all.html.twig', $build);
    }

/////////////////////////////////////////////////////////////CRUD Operations of Book////////////////////////////////////////////

#add
	public function addAction()
		{
			$adv = new advertising();
			$form = $this->createForm(new AdvertisingType(), $adv);
                        
                        $validator = $this->get('validator');
                        $errors = $validator->validate($adv);
		
			$request = $this->get('request');
			$form->handleRequest($request);
		
			if($request->getMethod() == 'POST')
			{

				if($form->isValid())
				{
					$em = $this->getDoctrine()->getManager();
					$em->persist($adv);
					$em->flush();
                                        
                $advs = $this->getDoctrine()
			      ->getRepository('AcmeDemoBundle:Advertising')
			      ->findAll();
					      if (!$advs) {
						throw $this->createNotFoundException('No advertisings found');
					      }
				      $build['advs'] = $advs;
				      return $this->render('AcmeDemoBundle:Advertising:advertising_show_all.html.twig', $build);
					
				}
			
			   return $this->render('AcmeDemoBundle:Advertising:add.html.twig',array('form'=>$form->createView())); 

			}

			   return $this->render('AcmeDemoBundle:Advertising:add.html.twig',array('form'=>$form->createView())); 

		}
#show	
       public function showAction($id) {
	      $adv = $this->getDoctrine()
			   ->getRepository('AcmeDemoBundle:Advertising')
			   ->find($id);
	      if (!$adv) {
		      throw $this->createNotFoundException('No advertising found by id ' . $id);
	      }
	    
	      $build['adv_item'] = $adv;
	      return $this->render('AcmeDemoBundle:Advertising:advertising_show.html.twig', $build);
	    }

#edit
	public function editAction($id, Request $request) {
	    $em = $this->getDoctrine()->getManager();
	    $adv = $em->getRepository('AcmeDemoBundle:Advertising')->find($id);

		$validator = $this->get('validator');
		$errors = $validator->validate($adv);   
         
	    if (!$adv) {
	      throw $this->createNotFoundException(
		      'No advertising found for id ' . $id
	      );
	    }
	    
	    $form = $this->createFormBuilder($adv)
			 ->add('body', 'textarea')
			 ->add('date','date', array(
                 'years' => range(date('Y'), date('Y')+50),
                     ))
			 ->add('submit','submit')
			 ->getForm();

	    $form->handleRequest($request);

	    if ($form->isValid()) {
					$em->flush();
					$advs = $this->getDoctrine()
						      ->getRepository('AcmeDemoBundle:Advertising')
						      ->findAll();
					      if (!$advs) {
						throw $this->createNotFoundException('No links found');
					      }
				      $build['advs'] = $advs;
			              return $this->render('AcmeDemoBundle:Advertising:advertising_show_all.html.twig', $build);

		// return new Response('News updated successfully');
	    }
	    
	    $build['form'] = $form->createView();

	    return $this->render('AcmeDemoBundle:Advertising:news_add.html.twig', $build);
	 }
		 
#delete
		 
		 public function deleteAction($id, Request $request) {

		    $em = $this->getDoctrine()->getManager();
		    $adv = $em->getRepository('AcmeDemoBundle:Advertising')->find($id);
		    if (!$adv) {
		      throw $this->createNotFoundException(
			      'No advertising found for id ' . $id
		      );
		    }

		      $em->remove($adv);
		      $em->flush();
		      	return $this->redirect($this->generateUrl('acme_advertising_home'));
		      return new Response('تم مسح بنجاح');

		    
		    $build['form'] = $form->createView();
		    return $this->render('AcmeDemoBundle:Advertising:news_add.html.twig', $build);
		}

	}
