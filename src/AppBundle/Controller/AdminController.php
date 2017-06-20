<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\RegisterType;

/**
 * Description of AdminController
 *
 * @author adrian
 */ 

/**
 * @Route("/admin")
 */
class AdminController extends Controller {

    /**
     * @Route("/", name="blog_admin_listing")
     * 
     * @Template
     */
    public function listingAction() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Register');

//        $rows = $repository->findBy(
//                array('country' => 'PL'), array('name' => 'ASC')
//        );

        $rows = $repository->findAll();
        return array(
            'rows' => $rows
        );
    }

    /**
     * @Route("/details/{id}", name="blog_admin_details")
     * 
     * @Template
     */
    public function detailsAction($id) {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Register');
        $row = $repository->find($id);

        if (NULL == $row) {
            throw $this->createNotFoundException('Nie znaleziono rejestracji!');
        }
        return array(
            'row' => $row
        );
    }

    /**
     * @Route("/update/{id}", name="blog_admin_update")
     * 
     * @Template
     */
    public function updateAction(Request $request, $id) {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Register');
        $row = $repository->find($id);
        $session = $this->get('session');
        if (NULL == $row) {
            throw $this->createNotFoundException('Nie znaleziono rejestracji!');
        }

        $form = $this->createForm(RegisterType::class, $row);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($row);
                $em->flush();

                $session->getFlashBag()->add('success', 'Dane zaktualizowane!');

                return $this->redirect($this->generateUrl('blog_admin_details', ['id' => $row->getId()
                ]));
            } else {
                $session->getFlashBag()->add('danger', 'Popraw dane formularza!');
            }
        }

        return array(
            'form' => $form->createView(),
            'row' => $row
        );
    }

    /**
     * @Route("/delete/{id}", name="blog_admin_delete")
     * 
     */
    public function deleteAction($id) {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Register');
        $row = $repository->find($id);
        $session = $this->get('session');
        
        if (NULL == $row) {
            throw $this->createNotFoundException('Nie znaleziono rejestracji!');
        }
        
        $em = $this ->getDoctrine()->getManager();
        $em->remove($row);
        $em->flush();
                
        $session->getFlashBag()->add('success', 'Rekord usunięty!');
        
        return $this->redirect($this->generateUrl('blog_admin_listing'));
    }

}
